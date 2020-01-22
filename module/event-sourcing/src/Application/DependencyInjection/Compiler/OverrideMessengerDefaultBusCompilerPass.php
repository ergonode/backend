<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Application\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Resolves Messenger Default Bus from the parameter messenger_default_bus
 */
class OverrideMessengerDefaultBusCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('override_messenger_default_bus')) {
            return;
        }
        $defaultBusParam = $container->getParameter('override_messenger_default_bus');
        $defaultBusId = $container->resolveEnvPlaceholders($defaultBusParam, true);

        $this->overrideDefaultBusService($container, $defaultBusId);
    }

    /**
     * This is same code as FrameworkExtension class lines 1755-1757
     *
     * @param ContainerBuilder $container
     * @param string           $defaultBusId
     */
    private function overrideDefaultBusService(ContainerBuilder $container, string $defaultBusId): void
    {
        $container
            ->setAlias('message_bus', $defaultBusId)
            ->setPublic(true)
            ->setDeprecated(
                true,
                'The "%alias_id%" service is deprecated, use the "messenger.default_bus" service instead.'
            );
        $container
            ->setAlias('messenger.default_bus', $defaultBusId)
            ->setPublic(true);
        $container
            ->setAlias(MessageBusInterface::class, $defaultBusId);
    }
}
