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

namespace LIN3S\SharedKernel\Infrastructure\Event\SimpleBus\EventRecorder\Doctrine\ORM;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use LIN3S\SharedKernel\Domain\Model\AggregateRoot;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class AggregateRootEventRecorder implements EventSubscriber, ContainsRecordedMessages
{
    private $collectedEvents;

    public function __construct()
    {
        $this->collectedEvents = [];
    }

    public function getSubscribedEvents() : array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        ];
    }

    public function postPersist(LifecycleEventArgs $event) : void
    {
        $this->collectEventsFromAggregateRoot($event);
    }

    public function postUpdate(LifecycleEventArgs $event) : void
    {
        $this->collectEventsFromAggregateRoot($event);
    }

    public function postRemove(LifecycleEventArgs $event) : void
    {
        $this->collectEventsFromAggregateRoot($event);
    }

    public function recordedMessages() : array
    {
        return $this->collectedEvents;
    }

    public function eraseMessages() : void
    {
        $this->collectedEvents = [];
    }

    private function collectEventsFromAggregateRoot(LifecycleEventArgs $event) : void
    {
        $entity = $event->getEntity();

        if ($entity instanceof AggregateRoot) {
            foreach ($entity->recordedEvents() as $event) {
                $this->collectedEvents[] = $event;
            }

            $entity->clearEvents();
        }
    }
}
