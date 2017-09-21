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

namespace LIN3S\SharedKernel\Event;

use LIN3S\SharedKernel\Domain\Model\DomainEvent;
use LIN3S\SharedKernel\Exception\InvalidArgumentException;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class StoredEvent
{
    private $id;
    private $type;
    private $payload;
    private $occurredOn;
    private $stream;

    public static function fromDomainEvent(DomainEvent $event, StreamName $stream) : self
    {
        $instance = new self(get_class($event), $event->occurredOn(), $stream);
        $instance->setPayload($event);

        return $instance;
    }

    private function setPayload(DomainEvent $event) : void
    {
        $this->payload = [];

        $eventReflection = new \ReflectionClass($event);
        foreach ($eventReflection->getProperties() as $property) {
            if ('occurredOn' === $property->name) {
                continue;
            }
            $property->setAccessible(true);
            // TODO: the following cast is not valid
            // what happens when the given property (Value object)
            // contains more than one attribute or it has not got implemented
            // the __toString() method ??
            // Furthermore, in the decodification process we need the class this property
            $this->payload[$property->getName()] = (string) $property->getValue($event);
        }
        $this->payload = json_encode($this->payload);
    }

    private function __construct(string $type, \DateTimeInterface $occurredOn, StreamName $stream)
    {
        $this->type = $type;
        $this->setOccurredOn($occurredOn);
        $this->stream = $stream->name();
    }

    private function setOccurredOn(\DateTimeInterface $occurredOn) : void
    {
        $this->checkDateTimeIsValid($occurredOn);
        $occurredOn->setTimezone(new \DateTimeZone('UTC'));
        $this->occurredOn = $occurredOn->getTimestamp();
    }

    public function toArray() : array
    {
        return [
            $this->type,
            $this->payload,
            $this->occurredOn,
            $this->stream,
        ];
    }

    private function checkDateTimeIsValid(\DateTimeInterface $occurredOn) : void
    {
        if (!($occurredOn instanceof \DateTimeImmutable) && !($occurredOn instanceof \DateTime)) {
            throw new InvalidArgumentException('Given occurredOn is not a \DateTime or \DateTimeImmutable instance');
        }
    }
}
