<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-present LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Infrastructure\Symfony\Bundle\DependencyInjection\Compiler;

use LIN3S\SharedKernel\Infrastructure\Persistence\Doctrine\DBAL\Domain\Model\Phone\Types\PhoneType;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DoctrineORMCustomTypesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $customTypes = $container->getParameter('doctrine.dbal.connection_factory.types');
        $customTypes = array_merge($customTypes, [
            'phone' => [
                'class'     => PhoneType::class,
                'commented' => true,
            ],
        ]);

        $container->setParameter('doctrine.dbal.connection_factory.types', $customTypes);
        $container->findDefinition('doctrine.dbal.connection_factory')->replaceArgument(
            0, '%doctrine.dbal.connection_factory.types%'
        );
    }
}
