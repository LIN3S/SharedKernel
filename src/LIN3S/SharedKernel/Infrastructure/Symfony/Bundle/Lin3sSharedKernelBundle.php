<?php

/*
 * This file is part of the CMS Kernel package.
 *
 * Copyright (c) 2016-present LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Infrastructure\Symfony\Bundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class Lin3sSharedKernelBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
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
                    'Lin3sSharedKernelPhone' => [
                        'type'      => 'xml',
                        'is_bundle' => false,
                        'dir'       => $this->basePath() . '/Phone/Mapping/',
                        'prefix'    => 'LIN3S\SharedKernel\Domain\Model\Phone',
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
