<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016 LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Spec\LIN3S\SharedKernel\Domain\Model\Identity;

use LIN3S\SharedKernel\Domain\Model\Identity\BaseId;
use LIN3S\SharedKernel\Domain\Model\Identity\Id;
use LIN3S\SharedKernel\Tests\Double\Domain\Model\Identity\IdStub;
use PhpSpec\ObjectBehavior;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class IdSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf(IdStub::class);
        $this->beConstructedGenerate(1);
    }

    function it_extends_id()
    {
        $this->shouldHaveType(Id::class);
    }

    function it_implements_base_id()
    {
        $this->shouldImplement(BaseId::class);
    }

    function it_generates_an_id()
    {
        $this::generate()->shouldReturnAnInstanceOf(IdStub::class);
        $this->id()->shouldReturn(1);
        $this->__toString()->shouldReturn('1');
    }

    function it_compares_two_ids(IdStub $id, IdStub $id2)
    {
        $id->id()->shouldBeCalled()->willReturn(1);
        $id2->id()->shouldBeCalled()->willReturn(2);

        $this->equals($id2)->shouldReturn(false);
        $this->equals($id)->shouldReturn(true);
    }
}
