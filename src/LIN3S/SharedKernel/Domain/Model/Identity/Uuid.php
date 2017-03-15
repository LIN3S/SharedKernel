<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-present LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Domain\Model\Identity;

use Ramsey\Uuid\Uuid as BaseUuid;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class Uuid
{
    public static function generate()
    {
        return BaseUuid::uuid4()->toString();
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }
}
