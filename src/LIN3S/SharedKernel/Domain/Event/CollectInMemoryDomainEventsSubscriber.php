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

namespace LIN3S\SharedKernel\Domain\Event;

use LIN3S\SharedKernel\Domain\Model\DomainEvent;
use LIN3S\SharedKernel\Domain\Model\PublishableDomainEvent;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class CollectInMemoryDomainEventsSubscriber implements DomainEventSubscriber
{
    private $events;

    public function __construct()
    {
        $this->events = [];
    }

    public function handle(DomainEvent $domainEvent) : void
    {
        if (!$this->isSubscribedTo($domainEvent)) {
            return;
        }
        $name = $domainEvent->name();
        $id = $domainEvent->aggregateId()->id();

        $this->events[$name][$id][] = $domainEvent->event();
    }

    public function isSubscribedTo(DomainEvent $domainEvent) : bool
    {
        return $domainEvent instanceof PublishableDomainEvent;
    }

    public function events() : array
    {
        return $this->events;
    }
}
