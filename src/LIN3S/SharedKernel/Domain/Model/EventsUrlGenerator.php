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

namespace LIN3S\SharedKernel\Domain\Model;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
interface EventsUrlGenerator
{
    public function generate(int $page) : string;
}
