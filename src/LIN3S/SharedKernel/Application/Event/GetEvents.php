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

use LIN3S\SharedKernel\Event\EventStore;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class GetEvents
{
    private $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function __invoke(GetEventsQuery $query) : array
    {
        $page = $query->page();
        $pageSize = $query->pageSize();
        $since = $query->since();

        $offset = ($page - 1) * $pageSize;

        return ['data' => ['lala' => 'lalala']];

        $events = $this->eventStore->eventsSince($since, $offset, $pageSize);

        return $this->response->build($events, $page, $pageSize);
    }
}
