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
    private const COLUMN_NAMES = ['type', 'payload', 'occurred_on', 'stream'];

    private $pdo;

    public function __construct(Pdo $pdo)
    {
        $this->pdo = $pdo;
    }

    public function append(Stream $stream) : void
    {
        $storedEvents = [];
        foreach ($stream->events() as $event) {
            $storedEvents[] = StoredEvent::fromDomainEvent($event, $stream->name());
        }

        if (count($storedEvents) === 0) {
            return;
        }

        $this->insert(...$storedEvents);
    }

    public function streamOfName(StreamName $name) : Stream
    {
        $tableName = self::TABLE_NAME;
        $sql = "SELECT * FROM `$tableName` WHERE stream = :stream ORDER BY id ASC";
        $storedEventRows = $this->pdo->execute($sql, ['stream' => $name->name()])->fetchAll(\PDO::FETCH_ASSOC);
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

    private function insert(StoredEvent ...$events) : void
    {
        $rowPlaces = '(' . implode(', ', array_fill(0, count(self::COLUMN_NAMES), '?')) . ')';
        $allPlaces = implode(', ', array_fill(0, count($events), $rowPlaces));

        $sql = 'INSERT INTO ' . self::TABLE_NAME . ' (' . implode(', ', self::COLUMN_NAMES) . ') VALUES ' . $allPlaces;

        $this->pdo->execute($sql, $this->prepareData(...$events));
    }

    private function prepareData(StoredEvent ...$events) : array
    {
        $data = [];
        foreach ($events as $event) {
            $data = array_merge($data, $event->toArray());
        }

        return $data;
    }

    public static function createSchema() : string
    {
        $tableName = self::TABLE_NAME;

        return <<<SQL
CREATE TABLE IF NOT EXISTS `$tableName` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(150) COLLATE utf8_bin NOT NULL,
  `payload` JSON NOT NULL,
  `occurred_on` INT(10) NOT NULL,
  `stream` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
SQL;
    }

    public static function removeSchema() : string
    {
        $tableName = self::TABLE_NAME;

        return "DROP TABLE `$tableName`";
    }
}
