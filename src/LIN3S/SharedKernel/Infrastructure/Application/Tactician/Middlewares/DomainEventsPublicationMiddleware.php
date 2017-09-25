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
use LIN3S\SharedKernel\Domain\Event\CollectInMemoryDomainEventsSubscriber;
use LIN3S\SharedKernel\Domain\Event\DomainEventPublisher;
use LIN3S\SharedKernel\Domain\Model\DomainEventCollection;
use LIN3S\SharedKernel\Event\AggregateId;
use LIN3S\SharedKernel\Event\EventStore;
use LIN3S\SharedKernel\Event\Stream;
use LIN3S\SharedKernel\Event\StreamName;
use LIN3S\SharedKernel\Event\StreamVersion;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DomainEventsPublicationMiddleware implements Middleware
{
    private $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function execute($command, callable $next)
    {
        $domainEventPublisher = DomainEventPublisher::instance();
        $collectDomainEventsSubscriber = new CollectInMemoryDomainEventsSubscriber();
        $domainEventPublisher->subscribe($collectDomainEventsSubscriber);

        $returnValue = $next($command);

        $eventsPerAggregate = $collectDomainEventsSubscriber->events();
        foreach ($eventsPerAggregate as $name => $aggregate) {
            foreach ($aggregate as $aggregateId => $domainEvents) {
                $this->eventStore->append(
                    new Stream(
                        StreamName::from(AggregateId::generate($aggregateId), $name),
                        $this->streamVersion(),
                        new DomainEventCollection($domainEvents)
                    )
                );
            }
        }

        return $returnValue;
    }

    private function streamVersion() : StreamVersion
    {
        return new StreamVersion(1);    // TODO: This value is hardcoded for now.
    }
}
