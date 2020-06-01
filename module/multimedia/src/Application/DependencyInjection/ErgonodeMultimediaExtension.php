<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaRelationInterface;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaRelationProvider;
use Ergonode\Multimedia\Application\DependencyInjection\CompilerPass\MultimediaRelationCompilerPass;

/**
 */
class ErgonodeMultimediaExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../Resources/config')
        );

        $container
            ->registerForAutoconfiguration(MultimediaRelationInterface::class)
            ->addTag(MultimediaRelationCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
