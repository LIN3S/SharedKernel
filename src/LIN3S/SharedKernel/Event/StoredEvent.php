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
class StoredEvent implements \JsonSerializable
{
    private $order;
    private $type;
    private $payload;
    private $serializedEvent;
    private $occurredOn;
    private $streamName;
    private $streamVersion;

    public function __construct(DomainEvent $event, StreamName $name, StreamVersion $version)
    {
        $this->type = get_class($event);
        $this->setOccurredOn($event->occurredOn());
        $this->streamName = $name->name();
        $this->streamVersion = $version->version();

        $this->setPayload($event);
        $this->setSerializedEvent($event);
    }

    public function normalizeToAppend() : array
    {
        return [
            'type'           => $this->type,
            'occurred_on'    => $this->occurredOn,
            'payload'        => $this->payload,
            'stream_name'    => $this->streamName,
            'stream_version' => $this->streamVersion,
        ];
    }

    public function jsonSerialize() : array
    {
        return [
            'order'          => $this->order,
            'type'           => $this->formatType(),
            'occurred_on'    => $this->occurredOn,
            'payload'        => $this->serializedEvent,
            'stream_name'    => $this->streamName,
            'stream_version' => $this->streamVersion,
        ];
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

        $this->serializedEvent = json_encode($this->serializedEvent);
    }

    private function setOccurredOn(\DateTimeInterface $occurredOn) : void
    {
        $this->checkDateTimeIsValid($occurredOn);
        $occurredOn->setTimezone(new \DateTimeZone('UTC'));
        $this->occurredOn = $occurredOn->getTimestamp();
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
        if (null === $value || is_scalar($value)) {
            return $value;
        }

        if ($property->isStatic()) {
            return $property->getValue();
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
        if (null === $value || is_scalar($value) || is_array($value)) {
            return $value;
        }

        $reflectionClass = new \ReflectionClass($value);
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            $result[] = $this->serializeEvent($property, $value, $result);
        }

        return $result;
    }

    private function formatType() : string
    {
        return mb_strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', array_reverse(explode('\\', $this->type))[0]));
    }
}
