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

use League\Tactician\Bundle\TacticianBundle;
use LIN3S\SharedKernel\Infrastructure\Application\Tactician\Middlewares\AppendDomainEventsToStoreMiddleware;
use LIN3S\SharedKernel\Infrastructure\Application\Tactician\Middlewares\DomainEventsPublicationMiddleware;
use LIN3S\SharedKernel\Infrastructure\Application\Tactician\Middlewares\PdoTransactionMiddleware;
use LIN3S\SharedKernel\Infrastructure\Application\Tactician\TacticianCommandBus;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class TacticianCommandBusPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container) : void
    {
        if (!class_exists(TacticianBundle::class) || !$container->hasDefinition('tactician.commandbus.default')) {
            return;
        }

        $container->setDefinition(
            'lin3s.application.tactician_command_bus',
            new Definition(TacticianCommandBus::class, [
                new Reference('tactician.commandbus'),
            ])
        );
        $container->setDefinition(
            'lin3s.tactician_middleware.pdo_transaction',
            new Definition(PdoTransactionMiddleware::class, [
                new Reference('lin3s.persistence.sql.pdo'),
            ])
        );
        $container->setDefinition(
            'lin3s.tactician_middleware.append_domain_events_to_store',
            new Definition(AppendDomainEventsToStoreMiddleware::class, [
                new Reference('lin3s.persistence.sql.event_store'),
            ])
        );
        $container->setDefinition(
            'lin3s.tactician_middleware.domain_events_publication',
            new Definition(DomainEventsPublicationMiddleware::class, [
                new Reference('lin3s.event_bus')
            ])
        );
    }
}
