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

use LIN3S\SharedKernel\Event\Stream;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
interface EventSourcedAggregateRoot
{
    public static function reconstitute(Stream $events);
}
