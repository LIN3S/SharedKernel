<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-2017 LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Domain\Model\Phone;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class Phone
{
    private $phone;

    public static function fromInternatinal($phone)
    {
        return new self($phone);
    }

    public static function fromRegion($region, $phone)
    {
        return new self($phone, $region);
    }

    public static function fromSpain($phone)
    {
        return new self($phone, 'ES');
    }

    private function __construct($phone, $region = null)
    {
        $this->setPhone($phone, $region);
    }

    public function equals(Phone $phone)
    {
        return $this->phone->phone() === $phone->phone();
    }

    public function phone()
    {
        return PhoneNumberUtil::getInstance()->format($this->phone, PhoneNumberFormat::E164);
    }

    public function phoneCallingFrom($region)
    {
        return PhoneNumberUtil::getInstance()->formatOutOfCountryCallingNumber($this->phoneNumber, $regionCode);
    }

    public function __toString()
    {
        return (string) $this->phone();
    }

    private function setPhone($phone, $region = null)
    {
        try {
            $this->phone = PhoneNumberUtil::getInstance()->parse($phone, $region);
            $this->checkIsValidNumber($this->phone);
        } catch (NumberParseException $exception) {
            throw new PhoneInvalidFormatException(
                $exception->getMessage()
            );
        }
    }

    private function checkIsValidNumber($phone, $region = null)
    {
        if (!PhoneNumberUtil::getInstance()->isValidNumber($this->phone)) {
            throw new PhoneInvalidFormatException();
        }
    }
}
