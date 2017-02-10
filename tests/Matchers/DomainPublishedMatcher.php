<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-2017 LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Tests\Matchers;

use LIN3S\SharedKernel\Domain\Model\AggregateRoot;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\Matcher\BasicMatcher;

/**
 * Usage:
 *    $this->shouldHavePublished(EventPublished::class);.
 *
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class DomainPublishedMatcher extends BasicMatcher
{
    protected function matches($subject, array $arguments)
    {
        foreach ($subject->recordedEvents() as $event) {
            if ($event instanceof $arguments[0]) {
                return true;
            }
        }

        return false;
    }

    protected function getFailureException($name, $subject, array $arguments)
    {
        return new FailureException(
            sprintf(
                'Expected an event of type %s to be published. Found other %d event(s)',
                $arguments[0],
                count($subject->recordedEvents())
            )
        );
    }

    protected function getNegativeFailureException($name, $subject, array $arguments)
    {
        return new FailureException(
            sprintf(
                'Expected an event of type %s not to be published but it was.',
                $arguments[0]
            )
        );
    }

    public function supports($name, $subject, array $arguments)
    {
        return $name === 'havePublished' && $subject instanceof AggregateRoot;
    }
}
