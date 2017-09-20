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

namespace LIN3S\SharedKernel\Infrastructure\Persistence\Doctrine\ORM\Event;

use Doctrine\ORM\EntityRepository;
use LIN3S\SharedKernel\Domain\Model\DomainEventCollection;
use LIN3S\SharedKernel\Event\EventStore;
use LIN3S\SharedKernel\Event\StoredEvent;
use LIN3S\SharedKernel\Event\Stream;
use LIN3S\SharedKernel\Event\StreamName;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DoctrineORMEventStore extends EntityRepository implements EventStore
{
    public function append(Stream $stream) : void
    {
        foreach ($stream->events() as $event) {
            $this->getEntityManager()->persist(
                StoredEvent::fromDomainEvent($event, $stream)
            );
        }
    }

    public function streamOfName(StreamName $name) : Stream
    {
        $storedEventsCollection = $this->findBy(['stream_name' => $name->name()]);
        $storedEvents = $storedEventsCollection->toArray();
        $domainEvents = $this->fromStoredEventsToDomainEvents($storedEvents);

        return new Stream($name, $events);
    }

    private function fromStoredEventsToDomainEvents(StoredEvent ...$storedEvents) : DomainEventCollection
    {
        $domainEvents = new DomainEventCollection();
        foreach ($storedEvents as $storedEvent) {
            $eventType = $storedEvent->type();
            $payload = json_decode($storedEvent['payload'], true);

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
}
