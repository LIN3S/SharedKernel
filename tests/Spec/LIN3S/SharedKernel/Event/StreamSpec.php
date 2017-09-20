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

namespace Spec\LIN3S\SharedKernel\Event;

use LIN3S\SharedKernel\Domain\Model\DomainEventCollection;
use LIN3S\SharedKernel\Domain\Model\Identity\Id;
use LIN3S\SharedKernel\Event\Stream;
use PhpSpec\ObjectBehavior;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class StreamSpec extends ObjectBehavior
{
    function it_can_be_created(Id $aggregateId, DomainEventCollection $events)
    {
        $this->beConstructedWith($aggregateId, $events);
        $this->shouldHaveType(Stream::class);
        $this->aggregateId()->shouldReturn($aggregateId);
        $this->events()->shouldReturn($events);
    }
}
