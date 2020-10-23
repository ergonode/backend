<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\DependencyInjection;

use Ergonode\Api\Application\Config\Definition\ErgonodeApiConfiguration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ErgonodeApiExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../Resources/config')
        );

        $loader->load('services.yml');

        $configuration = $this->processConfiguration(new ErgonodeApiConfiguration(), $configs);
        if (array_key_exists('exceptions', $configuration)) {
            $container->setParameter('ergonode_api.exceptions', $configuration['exceptions']);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias(): string
    {
        return 'ergonode_api';
    }
}
