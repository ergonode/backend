<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\DependencyInjection;

use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Ergonode\Channel\Application\Provider\ChannelFormFactoryInterface;
use Ergonode\Channel\Application\DependencyInjection\CompilerPass\ChannelFormFactoryCompilerPass;
use Ergonode\Channel\Domain\Entity\ChannelInterface;
use Ergonode\Channel\Application\DependencyInjection\CompilerPass\ChannelTypeCompilerPass;
use Ergonode\Channel\Application\Provider\CreateChannelCommandBuilderInterface;
use Ergonode\Channel\Application\DependencyInjection\CompilerPass\CreateChannelCommandBuilderCompilerPass;
use Ergonode\Channel\Application\Provider\UpdateChannelCommandBuilderInterface;
use Ergonode\Channel\Application\DependencyInjection\CompilerPass\UpdateChannelCommandBuilderCompilerPass;
use Ergonode\Channel\Application\DependencyInjection\CompilerPass\ExportProcessCompilerPass;
use Ergonode\Channel\Infrastructure\Processor\ExportProcessorInterface;

class ErgonodeChannelExtension extends Extension implements PrependExtensionInterface
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

        $container
            ->registerForAutoconfiguration(ChannelFormFactoryInterface::class)
            ->addTag(ChannelFormFactoryCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ChannelInterface::class)
            ->addTag(ChannelTypeCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(CreateChannelCommandBuilderInterface::class)
            ->addTag(CreateChannelCommandBuilderCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(UpdateChannelCommandBuilderInterface::class)
            ->addTag(UpdateChannelCommandBuilderCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ExportProcessorInterface::class)
            ->addTag(ExportProcessCompilerPass::TAG);

        $loader->load('services.yml');
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container): void
    {

        $this->prependNelmioApiDoc($container);
        $this->prependMessenger($container);
        $this->prependFlysystem($container);
        $this->prependMonolog($container);
    }

    private function prependNelmioApiDoc(ContainerBuilder $container): void
    {
        if (!in_array(NelmioApiDocBundle::class, $container->getParameter('kernel.bundles'), true)) {
            return;
        }
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));

        $loader->load('nelmio_api_doc.yaml');
    }

    private function prependMessenger(ContainerBuilder $container): void
    {
        $configs = $container->getExtensionConfig($this->getAlias());
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        if (!$this->isConfigEnabled($container, $config['messenger'])) {
            return;
        }

        $container->setParameter('ergonode.exporter.messenger_transport_name', $config['messenger']['transport_name']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));

        $loader->load('messenger.yaml');
    }

    private function prependFlysystem(ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));

        $loader->load('flysystem.yaml');
    }

    private function prependMonolog(ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));

        $loader->load('monolog.yaml');
    }
}
