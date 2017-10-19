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

namespace LIN3S\SharedKernel\Tests\Double\Domain\Model;

use LIN3S\SharedKernel\Domain\Model\DomainEvent;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DomainEventStub implements DomainEvent
{
    private $bar;
    private $foo;

    public function __construct($foo, $bar)
    {
        $this->bar = $bar;
        $this->foo = $foo;
    }

    public function occurredOn() : \DateTimeInterface
    {
        return new \DateTimeImmutable();
    }

    public function bar()
    {
        return $this->bar;
    }

    public function foo()
    {
        return $this->foo;
    }
}
