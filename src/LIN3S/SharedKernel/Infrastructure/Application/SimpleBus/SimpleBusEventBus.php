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

namespace LIN3S\SharedKernel\Infrastructure\Application\SimpleBus;

use LIN3S\SharedKernel\Application\EventBus;
use LIN3S\SharedKernel\Domain\Model\DomainEvent;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SimpleBusEventBus implements EventBus
{
    private $messageBus;

    public function __construct(MessageBusSupportingMiddleware $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function publish(DomainEvent ...$events) : void
    {
        foreach ($events as $event) {
            $this->messageBus->handle($event);
        }
    }
}
