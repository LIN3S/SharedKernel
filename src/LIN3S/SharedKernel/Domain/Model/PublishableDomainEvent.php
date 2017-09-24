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

namespace LIN3S\SharedKernel\Domain\Model;

use LIN3S\SharedKernel\Domain\Model\Identity\Id;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class PublishableDomainEvent implements DomainEvent
{
    private $aggregateId;
    private $name;
    private $event;

    public function __construct(Id $aggregateId, string $name, DomainEvent $event)
    {
        $this->aggregateId = $aggregateId;
        $this->name = $name;
        $this->event = $event;
    }

    public function aggregateId() : Id
    {
        return $this->aggregateId;
    }

    public function name() : string
    {
        return $this->name;
    }

    public function event() : DomainEvent
    {
        return $this->event;
    }

    public function occurredOn() : \DateTimeInterface
    {
        return $this->event->occurredOn();
    }
}
