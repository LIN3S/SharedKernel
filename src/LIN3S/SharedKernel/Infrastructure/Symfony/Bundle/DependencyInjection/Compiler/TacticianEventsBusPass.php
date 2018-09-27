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

use BornFree\TacticianDomainEvent\EventDispatcher\EventDispatcher;
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
    private $subscriberTag;

    public function __construct($subscriberTag = 'event_subscriber')
    {
        $this->subscriberTag = $subscriberTag;
    }

    public function process(ContainerBuilder $container) : void
    {
        if (!class_exists(TacticianDomainEventBundle::class)) {
            return;
        }

        $container->setDefinition(
            'tactician_domain_events.dispatcher',
            new Definition(EventDispatcher::class)
        )
            ->setPublic(false)
            ->setLazy(true);

        $container->setDefinition(
            'lin3s.application.tactician_event_bus',
            new Definition(TacticianEventBus::class, [
                new Reference('tactician_domain_events.dispatcher'),
            ])
        );
        $container->setAlias('lin3s.event_bus', 'lin3s.application.tactician_event_bus');

        $this->loadAllSubscribers($container);
        $this->addListeners($container);
    }

    private function loadAllSubscribers(ContainerBuilder $container) : void
    {
        $taggedServices = $container->findTaggedServiceIds($this->subscriberTag);

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $key => $attributes) {
                if (!isset($attributes['subscribes_to'])) {
                    throw new \Exception(sprintf(
                        '"subscribes_to" parameter not found in %s service definition tagged with "%s"',
                        $id,
                        $this->subscriberTag
                    ));
                }

                $container->setDefinition(
                    'tactician_event_subscriber.' . $id . '_' . $key,
                    new Definition(
                        TacticianEventSubscriber::class,
                        [new Reference($id)]
                    )
                )
                    ->setPublic(true)		
                    ->addTag('tactician.event_listener', ['event' => $attributes['subscribes_to']]);
            }
        }
    }

    private function addListeners(ContainerBuilder $container) : void
    {
        $definition = $container->getDefinition('tactician_domain_events.dispatcher');
        $taggedServices = $container->findTaggedServiceIds('tactician.event_listener');
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                if (!isset($attributes['event'])) {
                    throw new \Exception('The "tactician.event_listener" tag must always have an event attribute');
                }
                if (!class_exists($attributes['event'])) {
                    throw new \Exception(sprintf(
                        'Class %s registered as an event class in %s does not exist',
                        $attributes['event'],
                        $id
                    ));
                }
                $listener = array_key_exists('method', $attributes)
                    ? [new Reference($id), $attributes['method']]
                    : new Reference($id);
                $definition->addMethodCall('addListener', [
                    $attributes['event'],
                    $listener,
                ]);
            }
        }
    }
}
