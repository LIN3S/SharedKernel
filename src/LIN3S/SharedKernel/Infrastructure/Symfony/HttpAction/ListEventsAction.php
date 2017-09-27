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

namespace LIN3S\SharedKernel\Infrastructure\Symfony\HttpAction;

use LIN3S\SharedKernel\Application\Event\GetEvents;
use LIN3S\SharedKernel\Application\Event\GetEventsQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ListEventsAction
{
    private const PAGE_SIZE = 25;
    private const CACHE_LIFETIME = 60 * 60 * 24 * 365; // 1 year

    private $getEvents;

    public function __construct(GetEvents $getEvents)
    {
        $this->getEvents = $getEvents;
    }

    public function __invoke(Request $request) : JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $since = $request->query->get('since');

        $events = $this->getEvents->__invoke(new GetEventsQuery($page, self::PAGE_SIZE, $since));

        $numberOfEvents = count($events['data']);
        $isPageCompleted = self::PAGE_SIZE === $numberOfEvents;
        $response = new JsonResponse($events, 0 !== $numberOfEvents ? 200 : 404);

        return $isPageCompleted ? $this->cachedResponse($response) : $response;
    }

    private function cachedResponse(Response $response) : Response
    {
        return $response
            ->setMaxAge(self::CACHE_LIFETIME)
            ->setSharedMaxAge(self::CACHE_LIFETIME);
    }
}
