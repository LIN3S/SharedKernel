<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-present LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Domain\Event;

use LIN3S\SharedKernel\Domain\Model\DomainEvent;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DomainEventPublisher
{
    private static $instance;

    private $subscribers;
    private $id;

    public static function instance()
    {
        if (null === static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    private function __construct()
    {
        $this->subscribers = [];
        $this->id = 0;
    }

    public function __clone()
    {
        throw new DomainEventPublisherCloningIsNotAllowed();
    }

    public function subscribe(DomainEventSubscriber $domainEventSubscriber)
    {
        $id = $this->id;
        $this->subscribers[$id] = $domainEventSubscriber;
        $this->id++;

        return $id;
    }

    public function unsubscribe($id)
    {
        unset($this->subscribers[$id]);
    }

    public function publish(DomainEvent $domainEvent)
    {
        foreach ($this->subscribers as $aSubscriber) {
            if ($aSubscriber->isSubscribedTo($domainEvent)) {
                $aSubscriber->handle($domainEvent);
            }
        }
    }
}
