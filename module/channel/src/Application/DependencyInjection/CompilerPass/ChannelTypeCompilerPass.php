<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ergonode\Channel\Application\Provider\ChannelTypeProvider;

class ChannelTypeCompilerPass implements CompilerPassInterface
{
    public const TAG = 'channel.channel_interface';

    public function process(ContainerBuilder $container): void
    {
        if ($container->has(ChannelTypeProvider::class)) {
            $this->processHandler($container);
        }
    }

    private function processHandler(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(ChannelTypeProvider::class);
        $services = $container->findTaggedServiceIds(self::TAG);
        $types = [];

        foreach (array_keys($services) as $id) {
            $types[] = $container->getDefinition($id)->getClass()::getType();
        }

        $definition->setArguments($types);
    }
}
