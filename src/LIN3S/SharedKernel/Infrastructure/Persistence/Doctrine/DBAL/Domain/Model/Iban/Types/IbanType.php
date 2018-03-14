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

namespace LIN3S\SharedKernel\Infrastructure\Persistence\Doctrine\DBAL\Domain\Model\Iban\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\TextType;
use LIN3S\SharedKernel\Domain\Model\Iban\Iban;

/**
 * @author Rubén García <ruben@lin3s.com>
 */
class IbanType extends TextType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform) : ?string
    {
        if ($value instanceof Iban) {
            return $value->iban();
        }

        return $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform) : ?Iban
    {
        if (null === $value) {
            return;
        }

        return Iban::from($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform) : bool
    {
        return true;
    }

    public function getName()
    {
        return 'iban';
    }
}
