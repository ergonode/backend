<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Application\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Ergonode\ExporterFile\Infrastructure\Writer\WriterInterface;
use Ergonode\ExporterFile\Application\DependencyInjection\CompilerPass\FileWriterCompilerPass;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportTemplateElementBuilderInterface;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportProductBuilderInterface;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportAttributeBuilderInterface;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportOptionBuilderInterface;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportTemplateBuilderInterface;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportCategoryBuilderInterface;

class ErgonodeExporterFileExtension extends Extension implements PrependExtensionInterface
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
            ->registerForAutoconfiguration(WriterInterface::class)
            ->addTag(FileWriterCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(ExportTemplateBuilderInterface::class)
            ->addTag('export-file.template_builder');

        $container
            ->registerForAutoconfiguration(ExportTemplateElementBuilderInterface::class)
            ->addTag('export-file.template_element_builder');

        $container
            ->registerForAutoconfiguration(ExportCategoryBuilderInterface::class)
            ->addTag('export-file.category_builder');

        $container
            ->registerForAutoconfiguration(ExportProductBuilderInterface::class)
            ->addTag('export-file.product_builder');

        $container
            ->registerForAutoconfiguration(ExportAttributeBuilderInterface::class)
            ->addTag('export-file.attribute_builder');

        $container
            ->registerForAutoconfiguration(ExportOptionBuilderInterface::class)
            ->addTag('export-file.option_builder');

        $loader->load('services.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        $this->prependMessenger($container);
    }

    private function prependMessenger(ContainerBuilder $container): void
    {
        $configs = $container->getExtensionConfig($this->getAlias());
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        if (!$this->isConfigEnabled($container, $config['messenger'])) {
            return;
        }

        $container->setParameter(
            'ergonode.exporter_file.messenger_transport_name',
            $config['messenger']['transport_name'],
        );

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));

        $loader->load('messenger.yaml');
    }
}
