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

namespace LIN3S\SharedKernel\Domain\Model\Iban;

use IBAN\Core\IBAN as BaseIban;
use IBAN\Generation\IBANGeneratorES;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class Iban
{
    /** @var BaseIban */
    private $iban;

    public static function from(string $iban) : self
    {
        return new self($iban);
    }

    public static function fromSpain(string $instituteIdentification, string $bankAccountNumber) : self
    {
        return new self((new IBANGeneratorES())->generate($instituteIdentification, $bankAccountNumber));
    }

    private function __construct(string $iban)
    {
        $this->setIban($iban);
    }

    private function setIban(string $iban) : void
    {
        $iban = new BaseIban($iban);
        $this->checkIbanIsValid($iban);
        $this->iban = $iban;
    }

    private function checkIbanIsValid(BaseIban $iban) : void
    {
        if (!$iban->validate()) {
            throw new IbanInvalidException($iban->format());
        }
    }

    public function iban() : string
    {
        return $this->iban->format();
    }

    public function localCode() : string
    {
        return (string) $this->iban->getLocaleCode();
    }

    public function checksum() : string
    {
        return (string) $this->iban->getChecksum();
    }

    public function accountIdentification() : string
    {
        return (string) $this->iban->getAccountIdentification();
    }

    public function instituteIdentification() : string
    {
        return (string) $this->iban->getInstituteIdentification();
    }

    public function bankAccountNumber() : string
    {
        return (string) $this->iban->getBankAccountNumber();
    }

    public function equals(Iban $iban) : bool
    {
        return $this->iban() === $iban->iban();
    }

    public function __toString() : string
    {
        return (string) $this->iban();
    }
}
