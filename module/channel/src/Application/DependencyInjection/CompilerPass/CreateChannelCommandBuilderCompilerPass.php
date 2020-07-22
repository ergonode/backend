<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Ergonode\Channel\Application\Provider\CreateChannelCommandBuilderProvider;

/**
 */
class CreateChannelCommandBuilderCompilerPass implements CompilerPassInterface
{

    public const TAG = 'channel.create_channel_builder_interface';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->has(CreateChannelCommandBuilderProvider::class)) {
            $this->processServices($container);
        }
    }
    /**
     * @param ContainerBuilder $container
     */
    private function processServices(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(CreateChannelCommandBuilderProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach ($strategies as $id => $strategy) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
