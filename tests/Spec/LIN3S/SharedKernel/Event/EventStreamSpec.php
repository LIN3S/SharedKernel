<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016 LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Spec\LIN3S\SharedKernel\Event;

use LIN3S\SharedKernel\Domain\Model\DomainEventCollection;
use LIN3S\SharedKernel\Domain\Model\Identity\Id;
use LIN3S\SharedKernel\Event\EventStream;
use PhpSpec\ObjectBehavior;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class EventStreamSpec extends ObjectBehavior
{
    function let(Id $aggregateId, DomainEventCollection $events)
    {
        $this->beConstructedWith($aggregateId, $events);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EventStream::class);
    }

    function it_gets_aggregate_id(Id $aggregateId)
    {
        $this->aggregateId()->shouldReturn($aggregateId);
    }

    function it_gets_events(DomainEventCollection $events)
    {
        $this->events()->shouldReturn($events);
    }
}
