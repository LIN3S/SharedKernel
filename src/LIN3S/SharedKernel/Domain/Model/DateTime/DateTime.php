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

    public static function utcTimestamp()
    {
        return (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))->getTimestamp();
    }

    public static function timestamp()
    {
        return (new \DateTimeImmutable())->getTimestamp();
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }
}
