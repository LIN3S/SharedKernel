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

use LIN3S\SharedKernel\Domain\Model\DomainEventCollection;
use LIN3S\SharedKernel\Domain\Model\Identity\Id;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class EventStream
{
    private $aggregateId;
    private $events;

    public function __construct(Id $aggregateId, DomainEventCollection $events)
    {
        $this->aggregateId = $aggregateId;
        $this->events = $events;
    }

    public function aggregateId()
    {
        return $this->aggregateId;
    }

    public function events()
    {
        return $this->events;
    }
}
