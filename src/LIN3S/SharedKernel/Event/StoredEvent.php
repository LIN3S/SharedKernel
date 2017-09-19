<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-present LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Event;

use LIN3S\SharedKernel\Domain\Model\DomainEvent;
use LIN3S\SharedKernel\Domain\Model\DomainEventCollection;
use LIN3S\SharedKernel\Domain\Model\Identity\Id;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class StoredEvent implements DomainEvent
{
    private $id;
    private $type;
    private $payload;
    private $occurredOn;
    private $streamName;

    public function __construct($type, $payload, \DateTimeInterface $occurredOn, StreamName $streamName)
    {
        $this->type = $type;
        $this->payload = $payload;
        $this->occurredOn = $this->setOccurredOn($occurredOn);
        $this->streamName = $streamName->name();
    }

    private function setOccurredOn(\DateTimeInterface $occurredOn)
    {
        return (new \DateTimeImmutable($occurredOn->format('Y-m-d H:m:s'), new \DateTimeZone('UTC')))->getTimestamp();
    }

    public function occurredOn()
    {
        return $this->occurredOn;
    }
}
