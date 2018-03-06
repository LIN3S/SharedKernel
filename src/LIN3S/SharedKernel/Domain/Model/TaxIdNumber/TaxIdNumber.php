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

namespace LIN3S\SharedKernel\Domain\Model\TaxIdNumber;

use NifValidator\NifValidator;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class TaxIdNumber
{
    private $tin;

    private function __construct(string $tin)
    {
        $this->tin = $tin;
    }

    public static function fromSpain(string $nif) : self
    {
        if (!NifValidator::isValid($nif)) {
            throw TaxIdNumberInvalidException::fromSpain($nif);
        }

        return new self($nif);
    }

    public static function fromSpanishNie(string $nie) : self
    {
        if (!NifValidator::isValidNie($nie)) {
            throw TaxIdNumberInvalidException::fromSpanishNie($nie);
        }

        return new self($nie);
    }

    public static function fromSpanishCif(string $cif) : self
    {
        if (!NifValidator::isValidCif($cif)) {
            throw TaxIdNumberInvalidException::fromSpanishCif($cif);
        }

        return new self($cif);
    }

    public static function fromSpanishDni(string $dni) : self
    {
        if (!NifValidator::isValidDni($dni)) {
            throw TaxIdNumberInvalidException::fromSpanishDni($dni);
        }

        return new self($dni);
    }

    public function number() : string
    {
        return $this->tin;
    }

    public function equals(TaxIdNumber $tin) : bool
    {
        return $this->tin === $tin->number();
    }

    public function __toString() : string
    {
        return (string) $this->tin;
    }
}
