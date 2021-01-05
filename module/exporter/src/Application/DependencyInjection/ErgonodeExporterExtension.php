<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Exporter\Application\DependencyInjection;

use Ergonode\Exporter\Application\DependencyInjection\CompilerPass\ExportProcessCompilerPass;
use Ergonode\Exporter\Infrastructure\Factory\Command\RemoveExportArtifactsCommandFactoryInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Ergonode\Exporter\Infrastructure\Processor\ExportProcessorInterface;

class ErgonodeExporterExtension extends Extension implements PrependExtensionInterface
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
            ->registerForAutoconfiguration(ExportProcessorInterface::class)
            ->addTag(ExportProcessCompilerPass::TAG);


                $container
                    ->registerForAutoconfiguration(RemoveExportArtifactsCommandFactoryInterface::class)
                    ->addTag('export.remove_export_artifacts_command_factory_interface');

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

        $container->setParameter('ergonode.exporter.messenger_transport_name', $config['messenger']['transport_name']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));

        $loader->load('messenger.yaml');
    }
}
