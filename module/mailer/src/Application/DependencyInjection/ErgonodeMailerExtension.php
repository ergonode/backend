<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Mailer\Application\DependencyInjection;

use Ergonode\Mailer\Application\Config\Definition\ErgonodeMailerConfiguration;
use Ergonode\Mailer\Infrastructure\Sender\MailerStrategyInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 */
class ErgonodeMailerExtension extends Extension
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
        $loader->load('services.yml');

        $container
            ->registerForAutoconfiguration(MailerStrategyInterface::class)
            ->addTag('component.notification.mailer_strategy_interface');

        $configuration = $this->processConfiguration(new ErgonodeMailerConfiguration(), $configs);
        $container->setParameter('ergonode_mailer.from', $configuration['default']['from'] ?? null);
        $container->setParameter('ergonode_mailer.replyTo', $configuration['default']['replyTo'] ?? null);
    }
}
