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

use App\Domain\Model\Post\PostId;
use App\Domain\Model\Post\PostWasCreated;
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
    private $serializedEvent;
    private $occurredOn;
    private $streamName;
    private $streamVersion;

    public static function fromDomainEvent(DomainEvent $event, StreamName $name, StreamVersion $version) : self
    {
        $instance = new self(get_class($event), $event->occurredOn(), $name, $version);
        $instance->setPayload($event);
        $instance->setSerializedEvent($event);

        return $instance;
    }

    private function setPayload(DomainEvent $event) : void
    {
        $this->payload = [];
        $eventReflection = new \ReflectionClass($event);
        foreach ($eventReflection->getProperties() as $property) {
            if ('occurredOn' === $property->getName()) {
                continue;
            }
            $property->setAccessible(true);
            $this->payload[$property->getName()] = $this->serializePayload($property, $event);
        }
        $this->payload = json_encode($this->payload);
    }

    private function setSerializedEvent(DomainEvent $event) : void
    {
        $this->serializedEvent = [];
        $eventReflection = new \ReflectionClass($event);
        foreach ($eventReflection->getProperties() as $property) {
            if ('occurredOn' === $property->getName()) {
                continue;
            }
            $property->setAccessible(true);
            $this->serializedEvent[$property->getName()] = $this->serializeEvent($property, $event);
        }
    }

    private function __construct(string $type, \DateTimeInterface $occurredOn, StreamName $name, StreamVersion $version)
    {
        $this->type = $type;
        $this->setOccurredOn($occurredOn);
        $this->streamName = $name->name();
        $this->streamVersion = $version->version();
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
            'order'          => $this->id,
            'type'           => $this->type,
            'occurred_on'    => $this->occurredOn,
            'payload'        => $this->serializedEvent,
            'stream_name'    => $this->streamName,
            'stream_version' => $this->streamVersion,
        ];
    }

    public function persistableToArray() : array
    {
        return [
            $this->type,
            $this->payload,
            $this->occurredOn,
            $this->streamName,
            $this->streamVersion,
        ];
    }

    private function checkDateTimeIsValid(\DateTimeInterface $occurredOn) : void
    {
        if (!($occurredOn instanceof \DateTimeImmutable) && !($occurredOn instanceof \DateTime)) {
            throw new InvalidArgumentException('Given occurredOn is not a \DateTime or \DateTimeImmutable instance');
        }
    }

    private function serializePayload(\ReflectionProperty $property, $object, array $result = [])
    {
        $property->setAccessible(true);
        $value = $property->getValue($object);
        if (is_scalar($value)) {
            return $value;
        }

        $className = get_class($value);
        $reflectionClass = new \ReflectionClass($value);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $result[$className][$property->getName()] = $this->serializePayload($property, $value);
        }

        return $result;
    }

    private function serializeEvent(\ReflectionProperty $property, $object, array $result = [])
    {
        $property->setAccessible(true);
        $value = $property->getValue($object);
        if (is_scalar($value)) {
            return $value;
        }

        $className = get_class($value);
        $reflectionClass = new \ReflectionClass($value);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $childProperty) {
            $result[$property->getName()] = $this->serializeEvent($property, $value);
        }

        return $result;
    }
}
