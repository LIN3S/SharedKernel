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

namespace LIN3S\SharedKernel\Infrastructure\Persistence\Doctrine\DBAL\Domain\Model\TaxIdNumber\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TextType;
use LIN3S\SharedKernel\Domain\Model\TaxIdNumber\TaxIdNumber;

/**
 * @author Rubén García <ruben@lin3s.com>
 */
class TaxIdNumberType extends TextType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform) : ?string
    {
        if ($value instanceof TaxIdNumber) {
            return $value->number();
        }

        return $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform) : ?TaxIdNumber
    {
        if (null === $value) {
            return null;
        }

        return TaxIdNumber::fromSpain($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }

    public function getName() : string
    {
        return 'tax_id_number';
    }
}
