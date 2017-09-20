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

use LIN3S\SharedKernel\Exception\Exception;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ZipCodeInvalidException extends Exception
{
    public function __construct($zipCode, $iso)
    {
        parent::__construct(
            sprintf(
                'The "%s" is not a valid zip code for "%s" ISO code',
                $zipCode,
                $iso
            )
        );
    }
}
