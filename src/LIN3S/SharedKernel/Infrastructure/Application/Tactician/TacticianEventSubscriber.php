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

use LIN3S\SharedKernel\Domain\Event\DomainEventSubscriber;
use LIN3S\SharedKernel\Domain\Model\DomainEvent;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class TacticianEventSubscriber
{
    private $subscriber;

    public function __construct(DomainEventSubscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    public function __invoke(DomainEvent $event) : void
    {
        $this->subscriber->handle($event);
    }
}
