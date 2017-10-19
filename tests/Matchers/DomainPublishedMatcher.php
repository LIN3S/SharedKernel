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
    protected function matches($subject, array $arguments) : bool
    {
        foreach ($subject->recordedEvents() as $event) {
            if ($event instanceof $arguments[0]) {
                return true;
            }
        }

        return false;
    }

    protected function getFailureException(string $name, $subject, array $arguments) : FailureException
    {
        return new FailureException(
            sprintf(
                'Expected an event of type %s to be published. Found other %d event(s)',
                $arguments[0],
                count($subject->recordedEvents())
            )
        );
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments) : FailureException
    {
        return new FailureException(
            sprintf(
                'Expected an event of type %s not to be published but it was.',
                $arguments[0]
            )
        );
    }

    public function supports(string $name, $subject, array $arguments) : bool
    {
        return 'havePublished' === $name && $subject instanceof AggregateRoot;
    }
}
