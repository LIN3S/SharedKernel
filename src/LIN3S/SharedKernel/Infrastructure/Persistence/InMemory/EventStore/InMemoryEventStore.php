<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016 LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Infrastructure\Persistence\InMemory\EventStore;

use LIN3S\SharedKernel\Domain\Model\AggregateRootDoesNotExistException;
use LIN3S\SharedKernel\Domain\Model\DomainEventCollection;
use LIN3S\SharedKernel\Domain\Model\Identity\Id;
use LIN3S\SharedKernel\Event\EventStore;
use LIN3S\SharedKernel\Event\EventStream;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class InMemoryEventStore implements EventStore
{
    private $store;

    public function __construct()
    {
        $this->store = [];
    }

    public function appendTo(EventStream $stream)
    {
        foreach ($stream->events() as $event) {
            $content = [];
            $eventReflection = new \ReflectionClass($event);
            foreach ($eventReflection->getProperties() as $property) {
                $property->setAccessible(true);
                $content[$property->getName()] = $property->getValue($event);
            }

            $this->store[] = [
                'stream_id' => $stream->aggregateId(),
                'type'      => get_class($event),
                'content'   => json_encode($content),
            ];
        }
    }

    public function streamOfId(Id $aggregateId)
    {
        $events = new DomainEventCollection();
        foreach ($this->store as $event) {
            if ($event['stream_id'] === $aggregateId) {
                $eventData = json_decode($event['content']);
                $eventReflection = new \ReflectionClass($event['type']);
                $parameters = $eventReflection->getConstructor()->getParameters();
                $arguments = [];
                foreach ($parameters as $parameter) {
                    foreach ($eventData as $key => $data) {
                        if ($key === $parameter->getName()) {
                            $arguments[] = $data;
                        }
                    }
                }
                $events->add(new $event['type'](...$arguments));
            }
        }
        if (0 === $events->count()) {
            throw new AggregateRootDoesNotExistException($aggregateId);
        }

        return new EventStream($aggregateId, $events);
    }
}
