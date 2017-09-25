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

namespace LIN3S\SharedKernel\Infrastructure\Routing\Symfony;

use LIN3S\SharedKernel\Domain\Model\EventsUrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class SymfonyEventsUrlGenerator implements EventsUrlGenerator
{
    private $urlGenerator;
    private $pathName;

    public function __construct(UrlGeneratorInterface $urlGenerator, $pathName)
    {
        $this->urlGenerator = $urlGenerator;
        $this->pathName = $pathName;
    }

    public function generate(int $page) : string
    {
        return $this->urlGenerator->generate(
            $this->pathName,
            ['page' => (string) $page],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
