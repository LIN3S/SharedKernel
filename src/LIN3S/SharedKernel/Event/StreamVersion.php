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

namespace LIN3S\SharedKernel\Event;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class StreamVersion
{
    private $version;

    public function __construct(int $version)
    {
        $this->setVersion($version);
    }

    private function setVersion(int $version) : void
    {
        $this->checkVersionIsValid($version);
        $this->version = $version;
    }

    private function checkVersionIsValid(int $version) : void
    {
        if (0 >= $version) {
            throw new StreamVersionIsNotValid();
        }
    }

    public function version() : int
    {
        return $this->version;
    }

    public function __toString() : string
    {
        return (string) $this->version();
    }
}
