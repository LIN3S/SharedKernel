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

use LIN3S\SharedKernel\Domain\Model\DomainEvent;
use LIN3S\SharedKernel\Event\EventStore;
use LIN3S\SharedKernel\Event\StoredEvent;
use LIN3S\SharedKernel\Event\Stream;
use LIN3S\SharedKernel\Event\StreamName;
use LIN3S\SharedKernel\Event\StreamVersion;
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

    public function append(StoredEvent ...$events): void
    {
        $numberOfEvents = count($events);
        if (0 === $numberOfEvents) {
            return;
        }


        $parameters = [];
        foreach ($events as $event) {
            $parameters[] = [
                'type'           => $event->toArray()[0],
                'payload'        => $event->toArray()[1],
                'occurred_on'    => $event->toArray()[2],
                'stream_name'    => $event->toArray()[3],
                'stream_version' => $event->toArray()[4],
            ];
        }

        $this->pdo->insert(self::TABLE_NAME, $parameters);
    }

    public function streamOfName(StreamName $name): Stream
    {
        $tableName = self::TABLE_NAME;
        $sql = "SELECT * FROM `$tableName` WHERE stream_name = :streamName ORDER BY `order` ASC";
        $storedEventRows = $this->pdo->query($sql, ['streamName' => $name->name()]);
        $domainEventsCollection = $this->buildDomainEventsCollection($storedEventRows);

        return new Stream($name, $domainEventsCollection);
    }

    public function eventsSince(?\DateTimeInterface $since, int $offset = 0, int $limit = -1): array
    {
        $since = null === $since ? 0 : $since->getTimestamp();
        $tableName = self::TABLE_NAME;

        $sql = <<<SQL
SELECT * FROM `$tableName`
WHERE occurred_on >= :occurredOn
ORDER BY `order` ASC
LIMIT $limit
OFFSET $offset
SQL;

        $storedEventRows = $this->pdo->query($sql, ['occurredOn' => $since]);
        $storedEvents = $this->buildStoredEvents($storedEventRows);

        return $storedEvents;
    }

    private function buildStoredEvents(array $storedEventRows): array
    {
        $events = [];
        foreach ($storedEventRows as $storedEventRow) {
            $storedEvent = new StoredEvent(
                $this->buildDomainEvent($storedEventRow),
                StreamName::fromName($storedEventRow['stream_name']),
                new StreamVersion((int)$storedEventRow['stream_version'])
            );
            $orderProperty = new \ReflectionProperty(StoredEvent::class, 'order');
            $orderProperty->setAccessible(true);
            $orderProperty->setValue($storedEvent, $storedEventRow['order']);

            $events[] = $storedEvent;
        }

        return $events;
    }

    private function buildDomainEvent(array $storedEventRow): DomainEvent
    {
        $type = $storedEventRow['type'];
        $payload = json_decode($storedEventRow['payload'], true);

        $eventReflection = new \ReflectionClass($type);
        $domainEvent = $eventReflection->newInstanceWithoutConstructor();
        foreach ($eventReflection->getProperties() as $property) {
            $property->setAccessible(true);

            if ('occurredOn' === $property->getName()) {
                $occurredOn = new \DateTimeImmutable();
                $occurredOn->setTimestamp((int)$storedEventRow['occurred_on']);
                $property->setValue($domainEvent, $occurredOn);
                continue;
            }
            $this->unSerialize($property, $payload[$property->getName()], $domainEvent);
        }

        return $domainEvent;
    }

    private function unSerialize(\ReflectionProperty $reflectedProperty, $value, $object)
    {
        if (is_scalar($value)) {
            $reflectedProperty->setValue($object, $value);

            return $object;
        }

        $className = key($value);
        $reflectedClass = new \ReflectionClass($className);
        $class = $reflectedClass->newInstanceWithoutConstructor();
        $classValues = $value[$className];
        foreach ($reflectedClass->getProperties() as $property) {
            $property->setAccessible(true);
            $attribute = $this->unSerialize($property, $classValues[$property->getName()], $class);
            $reflectedProperty->setAccessible(true);
            $reflectedProperty->setValue($object, $attribute);
        }

        return $object;
    }

    public static function createSchema(): string
    {
        $tableName = self::TABLE_NAME;

        return <<<SQL
CREATE TABLE IF NOT EXISTS `$tableName` (
  `order` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(150) COLLATE utf8_bin NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `occurred_on` INT(10) NOT NULL,
  `stream_name` VARCHAR(255) NOT NULL,
  `stream_version` INT NOT NULL,
  PRIMARY KEY (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
SQL;
    }

    public static function removeSchema(): string
    {
        $tableName = self::TABLE_NAME;

        return "DROP TABLE `$tableName`";
    }
}
