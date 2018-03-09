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

use LIN3S\SharedKernel\Exception\DomainException;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class TaxIdNumberInvalidException extends DomainException
{
    public static function fromSpain(string $tin) : self
    {
        return new self(sprintf('The "%s" is not a valid NIF (DNI, NIE, or CIF) number.', $tin));
    }

    public static function fromSpanishNie(string $nie) : self
    {
        return new self(sprintf('The "%s" is not a valid Spanish NIE number.', $nie));
    }

    public static function fromSpanishCif(string $cif) : self
    {
        return new self(sprintf('The "%s" is not a valid Spanish CIF number.', $cif));
    }

    public static function fromSpanishDni(string $dni) : self
    {
        return new self(sprintf('The "%s" is not a valid Spanish DNI number.', $dni));
    }
}
