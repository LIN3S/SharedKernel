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

namespace LIN3S\SharedKernel\Domain\Model\ZipCode;

use Uvinum\ZipCode\Validator;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ZipCode
{
    private $zipCode;

    public static function fromSpain($zipCode)
    {
        return new self($zipCode, 'ES');
    }

    public function __construct($zipCode, $iso)
    {
        $this->setZipCode($zipCode, $iso);
    }

    public function zipCode()
    {
        return $this->zipCode;
    }

    public function equals(self $zipCode)
    {
        return $this->zipCode() === $zipCode->zipCode();
    }

    public function __toString()
    {
        return (string) $this->zipCode();
    }

    private function setZipCode($zipCode, $iso)
    {
        $zipCode = (string) $zipCode;

        $this->checkZipCodeIsCorrect($zipCode, $iso);
        $this->zipCode = $zipCode;
    }

    private function checkZipCodeIsCorrect($zipCode, $iso)
    {
        if (!(new Validator())->validate($iso, $zipCode)) {
            throw new ZipCodeInvalidException($zipCode, $iso);
        }
    }
}
