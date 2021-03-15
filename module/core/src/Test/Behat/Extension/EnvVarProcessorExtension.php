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
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\Compiler\ResolveEnvPlaceholdersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * This extension resolves env vars in behat.yml configuration files in same format and way as symfony.
 * @link https://symfony.com/doc/current/configuration/env_var_processors.html
 */
class EnvVarProcessorExtension implements ExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigKey()
    {
        return 'ergonode_env_var_processor';
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
        $container->addCompilerPass(
            new ResolveEnvPlaceholdersPass(),
            PassConfig::TYPE_BEFORE_OPTIMIZATION,
            -1000
        );

        $container->addCompilerPass(
            new ResolveEnvPlaceholdersPass(),
            PassConfig::TYPE_AFTER_REMOVING,
            -1000
        );
    }

    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
    }
}
