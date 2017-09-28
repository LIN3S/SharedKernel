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

namespace LIN3S\SharedKernel\Infrastructure\Application\Tactician\Middlewares;

use League\Tactician\Middleware;
use LIN3S\SharedKernel\Application\EventBus;
use LIN3S\SharedKernel\Domain\Event\CollectInMemoryDomainEventsSubscriber;
use LIN3S\SharedKernel\Domain\Event\DomainEventPublisher;
use LIN3S\SharedKernel\Domain\Model\PublishableDomainEvent;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DomainEventsPublicationMiddleware implements Middleware
{
    private $eventBus;

    public function __construct(EventBus $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function execute($command, callable $next)
    {
        $returnValue = $next($command);

        $collectDomainEventsSubscriber = DomainEventPublisher::instance()->subscriberOfClassName(
            CollectInMemoryDomainEventsSubscriber::class
        );
        $publishableEvents = $collectDomainEventsSubscriber->events();

        $domainEvents = array_map(function (PublishableDomainEvent $publishableDomainEvent) {
            return $publishableDomainEvent->event();
        }, $publishableEvents);

        $this->eventBus->publish(...$domainEvents);

        return $returnValue;
    }
}
