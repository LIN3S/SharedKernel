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

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
interface TranslatableSpecificationFactory
{
    public function createFilterTranslatable(array $filters, $page, $translatablesPerPage);
}
