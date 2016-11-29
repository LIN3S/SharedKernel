<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016 LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Spec\LIN3S\SharedKernel\Domain\Model;

use LIN3S\SharedKernel\Domain\Model\CollectionElementAlreadyAddedException;
use LIN3S\SharedKernel\Domain\Model\Exception;
use PhpSpec\ObjectBehavior;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class CollectionElementAlreadyAddedExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CollectionElementAlreadyAddedException::class);
    }

    function it_extends_exception()
    {
        $this->shouldHaveType(Exception::class);
    }
}
