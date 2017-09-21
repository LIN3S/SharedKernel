<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-present LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace LIN3S\SharedKernel\Infrastructure\Persistence\Sql\Event;

use LIN3S\SharedKernel\Domain\Model\DomainEventCollection;
use LIN3S\SharedKernel\Event\EventStore;
use LIN3S\SharedKernel\Event\StoredEvent;
use LIN3S\SharedKernel\Event\Stream;
use LIN3S\SharedKernel\Event\StreamName;
use LIN3S\SharedKernel\Infrastructure\Persistence\Sql\Pdo;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class SqlEventStore implements EventStore
{
    private const TABLE_NAME = 'events';
    private const COLUMN_NAMES = ['type', 'payload', 'occurred_on', 'stream_name', 'stream_version'];

    private $pdo;

    public function __construct(Pdo $pdo)
    {
        $this->pdo = $pdo;
    }

    public function append(Stream $stream) : void
    {
        $storedEvents = [];
        foreach ($stream->events() as $event) {
            $storedEvents[] = StoredEvent::fromDomainEvent($event, $stream->name(), $stream->version());
        }

        $numberOfEvents = count($storedEvents);
        if (0 === $numberOfEvents) {
            return;
        }

        $this->pdo->insert(self::TABLE_NAME, self::COLUMN_NAMES, $numberOfEvents, function () use ($storedEvents) {
            $data = [];
            foreach ($storedEvents as $event) {
                $data = array_merge($data, $event->toArray());
            }

            return $data;
        });
    }

    public function streamOfName(StreamName $name) : Stream
    {
        $tableName = self::TABLE_NAME;
        $sql = "SELECT * FROM `$tableName` WHERE stream_name = :stream_name ORDER BY id ASC";
        $storedEventRows = $this->pdo->query($sql, ['stream_name' => $name->name()]);
        $domainEventsCollection = $this->buildDomainEventsCollection($storedEventRows);

        return new Stream($name, $domainEventsCollection);
    }

    private function buildDomainEventsCollection(array $storedEventRows) : DomainEventCollection
    {
        $domainEvents = new DomainEventCollection();
        foreach ($storedEventRows as $storedEventRow) {
            $eventType = $storedEventRow['type'];
            $payload = json_decode($storedEventRow['payload'], true);

            $eventReflection = new \ReflectionClass($eventType);
            $domainEvent = $eventReflection->newInstanceWithoutConstructor();
            foreach ($eventReflection->getProperties() as $property) {
                $property->setAccessible(true);

                if (isset($payload[$property->name])) {
                    $property->setValue($domainEvent, $payload[$property->name]);
                    continue;
                }
                $property->setValue($domainEvent, $storedEventRow[$property]);
            }

            $domainEvents->add($domainEvent);
        }

        return $domainEvents;
    }

    public static function createSchema() : string
    {
        $tableName = self::TABLE_NAME;

        return <<<SQL
CREATE TABLE IF NOT EXISTS `$tableName` (
  `order` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(150) COLLATE utf8_bin NOT NULL,
  `payload` JSON NOT NULL,
  `occurred_on` INT(10) NOT NULL,
  `stream_name` VARCHAR(255) NOT NULL,
  `stream_version` INT NOT NULL,
  PRIMARY KEY (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
SQL;
    }

    public static function removeSchema() : string
    {
        $tableName = self::TABLE_NAME;

        return "DROP TABLE `$tableName`";
    }
}
