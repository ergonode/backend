<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\DependencyInjection;

use Ergonode\Importer\Application\DependencyInjection\CompilerPass\SourceFormFactoryCompilerPass;
use Ergonode\Importer\Infrastructure\Action\Process\Product\Strategy\ImportProductAttributeStrategyInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\SourceTypeCompilerPass;
use Ergonode\Importer\Infrastructure\Provider\ImportSourceInterface;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\ServiceCompilerPass;
use Ergonode\Importer\Application\Provider\SourceFormFactoryInterface;
use Ergonode\Importer\Infrastructure\Processor\SourceImportProcessorInterface;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\ServiceImportCompilerPass;
use Ergonode\Importer\Application\Provider\CreateSourceCommandBuilderInterface;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\CreateSourceCommandBuilderCompilerPass;
use Ergonode\Importer\Application\Provider\UpdateSourceCommandBuilderInterface;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\UpdateSourceCommandBuilderCompilerPass;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\ConverterMapperCompilerPass;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\TransformerGeneratorProviderStrategyCompilerPass;
use Ergonode\Importer\Application\DependencyInjection\CompilerPass\ConverterCompilerPass;
use Ergonode\Importer\Infrastructure\Converter\Mapper\ConverterMapperInterface;
use Ergonode\Importer\Infrastructure\Generator\TransformerGeneratorStrategyInterface;
use Ergonode\Importer\Infrastructure\Converter\ConverterInterface;

class ErgonodeImporterExtension extends Extension implements PrependExtensionInterface
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
            ->registerForAutoconfiguration(ImportSourceInterface::class)
            ->addTag(SourceTypeCompilerPass::TAG)
            ->addTag(ServiceCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(SourceFormFactoryInterface::class)
            ->addTag(SourceFormFactoryCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(CreateSourceCommandBuilderInterface::class)
            ->addTag(CreateSourceCommandBuilderCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(UpdateSourceCommandBuilderInterface::class)
            ->addTag(UpdateSourceCommandBuilderCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(SourceImportProcessorInterface::class)
            ->addTag(ServiceImportCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ConverterMapperInterface::class)
            ->addTag(ConverterMapperCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(TransformerGeneratorStrategyInterface::class)
            ->addTag(TransformerGeneratorProviderStrategyCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ConverterInterface::class)
            ->addTag(ConverterCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ImportProductAttributeStrategyInterface::class)
            ->addTag('ergonode.importer.attribute_strategy');

        $loader->load('services.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        $this->prependMessenger($container);
        $this->prependFlysystem($container);
    }

    private function prependMessenger(ContainerBuilder $container): void
    {
        $configs = $container->getExtensionConfig($this->getAlias());
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        if (!$this->isConfigEnabled($container, $config['messenger'])) {
            return;
        }

        $container->setParameter('ergonode.importer.messenger_transport_name', $config['messenger']['transport_name']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));

        $loader->load('messenger.yaml');
    }

    private function prependFlysystem(ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));

        $loader->load('flysystem.yaml');
    }
}
