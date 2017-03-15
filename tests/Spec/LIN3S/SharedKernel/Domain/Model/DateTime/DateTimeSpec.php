<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-present LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Spec\LIN3S\SharedKernel\Domain\Model\DateTime;

use LIN3S\SharedKernel\Domain\Model\DateTime\DateTime;
use PhpSpec\ObjectBehavior;

class DateTimeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(DateTime::class);
    }

    function it_builds_now()
    {
        $this->beConstructedNow();
        $this->shouldHaveType(\DateTimeImmutable::class);
    }

    function it_builds_from_time()
    {
        $this->beConstructedFromTime('2016-10-11');
        $this->shouldHaveType(\DateTimeImmutable::class);
    }

    function it_builds_from_relative()
    {
        $this->beConstructedFromRelative('+7 days');
        $this->shouldHaveType(\DateTimeImmutable::class);
    }
}
