<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-2017 LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Infrastructure\Application\SimpleBus;

use Ajgl\SimpleBus\Message\Bus\CatchReturnMessageBus;
use LIN3S\SharedKernel\Application\QueryBus;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SimpleBusQueryBus implements QueryBus
{
    private $messageBus;

    public function __construct(CatchReturnMessageBus $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function handle($query, &$result)
    {
        $this->messageBus->handle($query, $result);
    }
}