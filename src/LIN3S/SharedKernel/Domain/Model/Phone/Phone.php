<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016 LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Domain\Model\Phone;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class Phone
{
    private $phone;

    public function __construct($phone)
    {
        $this->phone = $this->setPhone($phone);
    }

    public function phone()
    {
        return $this->phone;
    }

    public function equals(Phone $phone)
    {
        return $this->phone === $phone->phone();
    }

    public function __toString()
    {
        return (string) $this->phone;
    }

    private function setPhone($phone)
    {
        $phone = str_replace('+34', '', $phone);
        $numbers = preg_replace('/\D/', '', $phone);

        if (!$numbers) {
            throw new PhoneInvalidFormatException();
        }

        return $numbers;
    }
}
