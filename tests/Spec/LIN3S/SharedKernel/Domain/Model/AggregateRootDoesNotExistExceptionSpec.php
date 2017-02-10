<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-2017 LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Spec\LIN3S\SharedKernel\Domain\Model;

use LIN3S\SharedKernel\Domain\Model\AggregateRootDoesNotExistException;
use LIN3S\SharedKernel\Domain\Model\Identity\Id;
use LIN3S\SharedKernel\Exception\Exception;
use PhpSpec\ObjectBehavior;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class AggregateRootDoesNotExistExceptionSpec extends ObjectBehavior
{
    function let(Id $aggregateId)
    {
        $aggregateId->__toString()->willReturn('id');
        $this->beConstructedWith($aggregateId);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AggregateRootDoesNotExistException::class);
    }

    function it_extends_exception()
    {
        $this->shouldHaveType(Exception::class);
    }
}
