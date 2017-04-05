<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-present LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Spec\LIN3S\SharedKernel\Domain\Model\Slug;

use LIN3S\SharedKernel\Domain\Model\Slug\Slug;
use PhpSpec\ObjectBehavior;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SlugSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Some plain text');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Slug::class);
    }

    function it_gets_slug()
    {
        $this->slug()->shouldReturn('some-plain-text');
        $this->__toString()->shouldReturn('some-plain-text');
    }
}
