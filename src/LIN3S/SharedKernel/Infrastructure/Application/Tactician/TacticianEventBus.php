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

namespace LIN3S\SharedKernel\Infrastructure\Application\Tactician;

use BornFree\TacticianDomainEvent\EventDispatcher\EventDispatcherInterface;
use LIN3S\SharedKernel\Application\EventBus;
use LIN3S\SharedKernel\Domain\Model\DomainEvent;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class TacticianEventBus implements EventBus
{
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function publish(DomainEvent ...$events) : void
    {
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }
}
