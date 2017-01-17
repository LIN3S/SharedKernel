<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016 LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Domain\Model;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class Phone
{
    private $phone;

    public function __construct($phone)
    {
        $this->phone = $phone ? $this->cleanPhone($phone) : '';
    }

    public function equals(Phone $phone)
    {
        return strtolower((string)$this) === strtolower((string)$phone);
    }

    public function __toString()
    {
        return (string)$this->phone;
    }

    private function cleanPhone($phone)
    {
        $absolute = $phone[0] === '+';
        $numbers = preg_replace('/\D/', '', $phone);

        if (!$numbers) {
            throw new PhoneFormatInvalidException();
        }

        // special logic for russian local phone notation
        if ($numbers[0] === '8' && !$absolute && strlen($numbers) == 11) {
            $numbers[0] = '7';
        }

        if ($numbers[0] !== '7' || strlen($numbers) != 11) {
            throw new PhoneFormatInvalidException();
        }

        return $numbers;
    }
}
