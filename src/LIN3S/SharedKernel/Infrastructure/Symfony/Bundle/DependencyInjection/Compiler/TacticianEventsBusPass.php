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

namespace LIN3S\SharedKernel\Infrastructure\Symfony\Bundle\DependencyInjection\Compiler;

use BornFree\TacticianDomainEventBundle\TacticianDomainEventBundle;
use LIN3S\SharedKernel\Infrastructure\Application\Tactician\TacticianEventBus;
use LIN3S\SharedKernel\Infrastructure\Application\Tactician\TacticianEventSubscriber;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class TacticianEventsBusPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container) : void
    {
        if (!class_exists(TacticianDomainEventBundle::class)
            || !$container->hasDefinition('tactician_domain_events.dispatcher')) {
            return;
        }

        $container->setDefinition(
            'lin3s.application.tactician_event_bus',
            new Definition(TacticianEventBus::class, [
                new Reference('tactician_domain_events.dispatcher'),
            ])
        );
        $container->setAlias('lin3s.event_bus', 'lin3s.application.tactician_event_bus');

        $this->loadAllSubscribers($container);
    }

    private function loadAllSubscribers(ContainerBuilder $container) : void
    {
        $taggedServices = $container->findTaggedServiceIds('event_subscriber');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                if (!isset($attributes['subscribes_to'])) {
                    throw new \Exception(sprintf(
                        '"subscribes_to" parameter not found in %s service definition tagged with "event_subscriber"',
                        $id
                    ));
                }

                $container->setDefinition(
                    'tactician_event_subscriber.' . $id,
                    new Definition(
                        TacticianEventSubscriber::class,
                        [new Reference($id)]
                    )
                )->addTag('tactician.event_listener', ['event' => $attributes['subscribes_to']]);
            }
        }
    }
}
