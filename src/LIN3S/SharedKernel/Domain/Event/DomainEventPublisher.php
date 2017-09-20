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

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class DomainEventPublisher
{
    private static $instance;

    private $subscribers;

    private function __construct()
    {
        $this->subscribers = [];
    }

    public function __clone()
    {
        throw new DomainEventPublisherCloningIsNotAllowed();
    }

    public static function instance() : self
    {
        if (null === static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    public function subscribe(DomainEventSubscriber $domainEventSubscriber) : void
    {
        $this->subscribers[] = $domainEventSubscriber;
    }

    public function publish(DomainEvent $domainEvent) : void
    {
        foreach ($this->subscribers as $subscriber) {
            if ($subscriber->isSubscribedTo($domainEvent)) {
                $subscriber->handle($domainEvent);
            }
        }
    }
}
