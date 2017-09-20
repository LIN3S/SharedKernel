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

use League\Tactician\CommandBus as Tactician;
use LIN3S\SharedKernel\Application\CommandBus;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class TacticianCommandBus implements CommandBus
{
    private $commandBus;

    public function __construct(Tactician $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle($command) : void
    {
        $this->commandBus->handle($command);
    }
}
