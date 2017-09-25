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

namespace LIN3S\SharedKernel\Application\Event;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class GetEventsQuery
{
    private $page;
    private $pageSize;
    private $since;

    public function __construct(int $page, int $pageSize, string $since = null)
    {
        $this->page = $page;
        $this->pageSize = $pageSize;
        $this->since = null === $since ? null : new \DateTimeImmutable($since);
    }

    public function page() : int
    {
        return $this->page;
    }

    public function pageSize() : int
    {
        return $this->pageSize;
    }

    public function since() : ?\DateTimeInterface
    {
        return $this->since;
    }
}
