<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-present LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Infrastructure\Application\Tactician\Middleware;

use League\Tactician\Middleware;
use LIN3S\SharedKernel\Domain\Event\CollectlDomainEventsSubscriber;
use LIN3S\SharedKernel\Domain\Event\DomainEventPublisher;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DomainEventsPublicationMiddleware implements Middleware
{
    public function execute($command, callable $next)
    {
        $domainEventPublisher = DomainEventPublisher::instance();
        $collectDomainEventsSubscriber = new CollectlDomainEventsSubscriber();
        $domainEventPublisher->subscribe($collectDomainEventsSubscriber);

        $returnValue = $next($command);

        $domainEvents = $collectDomainEventsSubscriber->events();
        foreach ($domainEvents as $domainEvent) {
            $domainEventPublisher->publish($domainEvent);
        }

        return $returnValue;
    }
}
