<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-present LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Event;

use LIN3S\SharedKernel\Domain\Model\DomainEventCollection;
use LIN3S\SharedKernel\Domain\Model\Identity\Id;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class Stream
{
    private $name;
    private $events;

    public function __construct(StreamName $name, DomainEventCollection $events)
    {
        $this->name = $name;
        $this->events = $events;
    }

    public function name()
    {
        return $this->name;
    }

    public function events()
    {
        return $this->events;
    }
}
