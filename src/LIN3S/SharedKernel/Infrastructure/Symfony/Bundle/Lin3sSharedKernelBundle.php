<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-present LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Infrastructure\Symfony\Bundle;

use LIN3S\SharedKernel\Infrastructure\Persistence\Doctrine\ORM\Types\PhoneType;
use LIN3S\SharedKernel\Infrastructure\Symfony\Bundle\DependencyInjection\Compiler\DoctrineORMCustomTypesPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class Lin3sSharedKernelBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DoctrineORMCustomTypesPass(), PassConfig::TYPE_OPTIMIZE);

        $container->loadFromExtension('doctrine', [
            'orm' => [
                'mappings' => [
                    'Lin3sSharedKernelEmail' => [
                        'type'      => 'xml',
                        'is_bundle' => false,
                        'dir'       => $this->basePath() . '/Email/Mapping/',
                        'prefix'    => 'LIN3S\SharedKernel\Domain\Model\Email',
                    ],
                    'Lin3sSharedKernelSlug'  => [
                        'type'      => 'xml',
                        'is_bundle' => false,
                        'dir'       => $this->basePath() . '/Slug/Mapping/',
                        'prefix'    => 'LIN3S\SharedKernel\Domain\Model\Slug',
                    ],
                ],
            ],
        ]);
    }

    private function basePath()
    {
        $directory = dirname((new \ReflectionClass(self::class))->getFileName());

        return $directory . '/../../Persistence/Doctrine/ORM';
    }
}
