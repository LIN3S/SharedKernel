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

use LIN3S\SharedKernel\Domain\Model\BaseAggregateRoot;
use LIN3S\SharedKernel\Domain\Model\EventSourcedAggregateRoot;
use LIN3S\SharedKernel\Domain\Model\Identity\Id;
use LIN3S\SharedKernel\Event\EventStream;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class AggregateRootStub extends BaseAggregateRoot implements EventSourcedAggregateRoot
{
    private $id;
    private $property;

    public function __construct(Id $id)
    {
        $this->id = $id;
    }

    public function id()
    {
        return $this->id;
    }

    public function property()
    {
        return $this->property;
    }

    public function foo()
    {
        $this->publish(new EventSourcingEventStub());
    }

    public function bar()
    {
        $this->property = 'bar';
        $this->publish(new CqrsEventStub());
    }

    protected function applyEventSourcingEventStub()
    {
        $this->property = 'foo';
    }

    public static function reconstitute(EventStream $events)
    {
        $instance = new self($events->aggregateId());

        foreach ($events as $event) {
            $instance->apply($event);
        }

        return $instance;
    }
}
