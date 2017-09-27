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

namespace LIN3S\SharedKernel\Event;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
interface EventStore
{
    public function append(StoredEvent ...$events) : void;

    public function streamOfName(StreamName $name) : Stream;

    public function eventsSince(?\DateTimeInterface $since, int $offset = 0, int $limit = -1) : array;
}
