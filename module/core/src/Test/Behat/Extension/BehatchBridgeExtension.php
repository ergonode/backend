<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Extension;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Extension for Behatch bridge.
 */
class BehatchBridgeExtension implements ExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigKey()
    {
        return 'behatch_bridge';
    }

    /**
     * {@inheritDoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $container->getDefinition('behatch.http_call.request')->setPublic(true);
    }

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
    }
}
