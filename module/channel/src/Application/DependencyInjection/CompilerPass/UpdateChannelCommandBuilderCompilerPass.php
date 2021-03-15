<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Ergonode\Channel\Application\Provider\UpdateChannelCommandBuilderProvider;

class UpdateChannelCommandBuilderCompilerPass implements CompilerPassInterface
{
    public const TAG = 'channel.update_channel_builder_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(UpdateChannelCommandBuilderProvider::class)) {
            $this->processServices($container);
        }
    }

    private function processServices(ContainerBuilder $container): void
    {
        $arguments = [];
        $definition = $container->findDefinition(UpdateChannelCommandBuilderProvider::class);
        $strategies = $container->findTaggedServiceIds(self::TAG);

        foreach (array_keys($strategies) as $id) {
            $arguments[] = new Reference($id);
        }

        $definition->setArguments($arguments);
    }
}
