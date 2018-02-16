<?php

/*
 * This file is part of the CMS Kernel package.
 *
 * Copyright (c) 2016-present LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Domain\Model\Translation;

use LIN3S\SharedKernel\Domain\Model\Locale\Locale;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
abstract class Translation
{
    protected $locale;
    protected $origin;

    public function __construct(Locale $locale)
    {
        $this->locale = $locale;
    }

    public function locale() : Locale
    {
        return $this->locale;
    }

    public function origin()
    {
        return $this->origin;
    }
}
