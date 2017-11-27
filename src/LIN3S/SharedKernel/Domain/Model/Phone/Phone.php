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

namespace LIN3S\SharedKernel\Domain\Model\Phone;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class Phone
{
    private $phone;

    public static function fromInternational($phone) : self
    {
        return new self($phone);
    }

    public static function fromRegion($region, $phone) : self
    {
        return new self($phone, $region);
    }

    public static function fromSpain($phone) : self
    {
        return new self($phone, 'ES');
    }

    private function __construct($phone, string $region = null)
    {
        $this->setPhone($phone, $region);
    }

    public function equals(self $phone) : bool
    {
        return $this->phone() === $phone->phone();
    }

    public function phone() : string
    {
        return PhoneNumberUtil::getInstance()->format($this->phone, PhoneNumberFormat::E164);
    }

    public function phoneCallingFrom($region) : string
    {
        return PhoneNumberUtil::getInstance()->formatOutOfCountryCallingNumber($this->phone, $region);
    }

    public function __toString() : string
    {
        return (string) $this->phone();
    }

    private function setPhone($phone, ?string $region) : void
    {
        try {
            $phone = PhoneNumberUtil::getInstance()->parse($phone, $region);
            $this->checkIsValidNumber($phone);
            $this->phone = $phone;
        } catch (NumberParseException $exception) {
            throw new PhoneInvalidFormatException(
                $exception->getMessage()
            );
        }
    }

    private function checkIsValidNumber(PhoneNumber $phone) : void
    {
        if (!PhoneNumberUtil::getInstance()->isValidNumber($phone)) {
            throw new PhoneInvalidFormatException();
        }
    }
}
