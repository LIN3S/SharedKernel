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

use LIN3S\SharedKernel\Domain\Model\EventsUrlGenerator;
use LIN3S\SharedKernel\Event\EventStore;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class GetEvents
{
    private $eventStore;
    private $urlGenerator;

    public function __construct(EventStore $eventStore, EventsUrlGenerator $urlGenerator)
    {
        $this->eventStore = $eventStore;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(GetEventsQuery $query) : array
    {
        $page = $query->page();
        $pageSize = $query->pageSize();
        $since = $query->since();

        $offset = ($page - 1) * $pageSize;

        $events = $this->eventStore->eventsSince($since, $offset, $pageSize);

        return $this->response($events, $page, $pageSize);
    }

    public function response(array $events, int $page, int $pageSize) : array
    {
        return [
            '_meta'  => [
                'count' => $this->numberOfEvents($events),
                'page'  => $page,
            ],
            '_links' => $this->links($events, $page, $pageSize),
            'data'   => $events,
        ];
    }

    private function numberOfEvents(array $events) : int
    {
        return count($events);
    }

    private function links(array $events, int $page, int $pageSize) : array
    {
        $links = [
            'first' => $this->urlGenerator->generate(1),
            'self'  => $this->urlGenerator->generate($page),
        ];
        if ($this->numberOfEvents($events) === $pageSize) {
            $links = array_merge(
                ['first' => $this->urlGenerator->generate(1)],
                ['self' => $links['self']],
                ['next' => $this->urlGenerator->generate($page + 1)]
            );
        }

        return $links;
    }
}
