<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016 LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Spec\LIN3S\SharedKernel\Infrastructure\Persistence\InMemory\EventStore;

use LIN3S\SharedKernel\Domain\Model\AggregateDoesNotExistException;
use LIN3S\SharedKernel\Domain\Model\DomainEventCollection;
use LIN3S\SharedKernel\Domain\Model\Identity\Id;
use LIN3S\SharedKernel\Event\EventStore;
use LIN3S\SharedKernel\Event\EventStream;
use LIN3S\SharedKernel\Infrastructure\Persistence\InMemory\EventStore\InMemoryEventStore;
use LIN3S\SharedKernel\Tests\Double\Domain\Model\DomainEventStub;
use PhpSpec\ObjectBehavior;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class InMemoryEventStoreSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(InMemoryEventStore::class);
    }

    function it_implements_event_store()
    {
        $this->shouldImplement(EventStore::class);
    }

    function it_appends_to(EventStream $stream, Id $aggregateId)
    {
        $eventCollection = new DomainEventCollection([
            new DomainEventStub('foo', 'bar'),
        ]);
        $stream->events()->shouldBeCalled()->willReturn($eventCollection);
        $stream->aggregateId()->shouldBeCalled()->willReturn($aggregateId);
        $this->appendTo($stream);
    }

    function it_get_stream_of_id_given(EventStream $stream, Id $aggregateId)
    {
        $aggregateId->id()->willReturn('aggregate-id');
        $eventCollection = new DomainEventCollection([
            new DomainEventStub('foo', 'bar'),
        ]);
        $stream->events()->shouldBeCalled()->willReturn($eventCollection);
        $stream->aggregateId()->shouldBeCalled()->willReturn($aggregateId);
        $this->appendTo($stream);

        $this->streamOfId($aggregateId)->shouldReturnAnInstanceOf(EventStream::class);
    }

    function it_does_not_get_any_aggregate(Id $aggregateId)
    {
        $aggregateId->__toString()->willReturn('id');

        $this->shouldThrow(AggregateDoesNotExistException::class)->duringStreamOfId($aggregateId);
    }
}
