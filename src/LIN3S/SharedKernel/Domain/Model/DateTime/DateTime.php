<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-present LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Domain\Model\DateTime;

final class DateTime
{
    public static function now()
    {
        return new \DateTimeImmutable();
    }

    public static function fromTime($time)
    {
        return new \DateTimeImmutable($time);
    }

    public static function fromRelative($relative)
    {
        return new \DateTimeImmutable($relative);
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }
}
