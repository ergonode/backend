<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Application\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ErgonodeExporterShopware6Extension extends Extension implements PrependExtensionInterface
{
    /**
     * @param array $configs
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../Resources/config')
        );

        $loader->load('services.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        $this->prependMessenger($container);
    }

    private function prependMessenger(ContainerBuilder $container): void
    {
        $configs = $container->getExtensionConfig($this->getAlias());
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        if (!$this->isConfigEnabled($container, $config['messenger'])) {
            return;
        }

        $container->setParameter(
            'ergonode.exporter_shopware_6.messenger_transport_name',
            $config['messenger']['transport_name'],
        );

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));

        $loader->load('messenger.yaml');
    }
}
