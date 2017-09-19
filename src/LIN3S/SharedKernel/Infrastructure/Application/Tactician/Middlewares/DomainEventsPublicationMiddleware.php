<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-present LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Infrastructure\Application\Tactician\Middlewares;

use App\Domain\Model\Post\PublicProject\Participant\ParticipantId;
use League\Tactician\Middleware;
use LIN3S\SharedKernel\Domain\Event\CollectDomainEventsSubscriber;
use LIN3S\SharedKernel\Domain\Event\DomainEventPublisher;
use LIN3S\SharedKernel\Domain\Model\DomainEventCollection;
use LIN3S\SharedKernel\Event\EventStore;
use LIN3S\SharedKernel\Event\Stream;
use LIN3S\SharedKernel\Event\StreamName;

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
        $collectDomainEventsSubscriber = new CollectDomainEventsSubscriber();
        $domainEventPublisher->subscribe($collectDomainEventsSubscriber);

        $returnValue = $next($command);

        $domainEvents = $collectDomainEventsSubscriber->events();
        $aggregateId = ParticipantId::generate('dummy-id'); // $domainEvents[0]->aggregateId()
        $name = '@TODO'; // // $domainEvents[0]->name()

        $this->eventStore->append(
            new Stream(
                new StreamName($aggregateId, $name),
                new DomainEventCollection($domainEvents)
            )
        );

        return $returnValue;
    }
}
