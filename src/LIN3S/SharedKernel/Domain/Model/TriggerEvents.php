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

use LIN3S\SharedKernel\Domain\Event\DomainEventPublisher;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
trait TriggerEvents
{
    private $recordedEvents = [];

    abstract public function id();

    public function recordedEvents() : array
    {
        return $this->recordedEvents;
    }

    public function clearEvents() : void
    {
        $this->recordedEvents = [];
    }

    protected function publish(DomainEvent $event) : void
    {
        $this->apply($event);
        $this->record($event);
        DomainEventPublisher::instance()->publish($event);
    }

    protected function apply(DomainEvent $event) : void
    {
        $modifier = 'apply' . array_reverse(explode('\\', get_class($event)))[0];
        if (!method_exists($this, $modifier)) {
            return;
        }
        $this->$modifier($event);
    }

    private function record(DomainEvent $event) : void
    {
        $this->recordedEvents[] = $event;
    }
}
