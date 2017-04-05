<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-present LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Tests\Matchers;

use LIN3S\SharedKernel\Domain\Model\Collection;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Matcher\BasicMatcher;

/**
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class CollectionMatcher extends BasicMatcher
{
    protected function matches($subject, array $arguments)
    {
        return $subject->toArray() === $arguments[0];
    }

    protected function getFailureException($name, $subject, array $arguments)
    {
        return new FailureException(
            'Expected to match Collection but it doesn`t'
        );
    }

    protected function getNegativeFailureException($name, $subject, array $arguments)
    {
        return new FailureException(
            'Expected not to match Collection but it does'
        );
    }

    public function supports($name, $subject, array $arguments)
    {
        return $name === 'returnCollection' && $subject instanceof Collection;
    }
}
