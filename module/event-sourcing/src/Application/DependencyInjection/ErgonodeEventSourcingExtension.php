<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Application\DependencyInjection;

use Ergonode\EventSourcing\Infrastructure\Manager\Decorator\EventStoreManagerCacheDecorator;
use Ergonode\EventSourcing\Infrastructure\Snapshot\DbalAggregateSnapshot;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class ErgonodeEventSourcingExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../Resources/config')
        );

        $loader->load('services.yml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container
            ->getDefinition(DbalAggregateSnapshot::class)
            ->setArgument(
                '$snapshotEvents',
                $config['snapshot_frequency'],
            );

        $this->loadCache($config, $loader, $container);
    }

    private function loadCache(array $config, LoaderInterface $loader, ContainerBuilder $container): void
    {
        if (!isset($config['aggregate_root_cache'])) {
            return;
        }

        $loader->load('cache.yaml');

        $container
            ->getDefinition(EventStoreManagerCacheDecorator::class)
            ->setArgument(
                '$adapter',
                new Reference($config['aggregate_root_cache']),
            );
    }
}
