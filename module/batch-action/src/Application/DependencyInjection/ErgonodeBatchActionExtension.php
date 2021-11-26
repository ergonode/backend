<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\DependencyInjection;

use Ergonode\BatchAction\Application\Form\BatchActionFormInterface;
use Ergonode\BatchAction\Domain\Count\CountInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Ergonode\BatchAction\Application\Form\BatchActionReprocessFormInterface;

class ErgonodeBatchActionExtension extends Extension implements PrependExtensionInterface
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

        $loader->load('services.yml');

        $container
            ->registerForAutoconfiguration(CountInterface::class)
            ->addTag('ergonode.batch_action.count');
        $container
            ->registerForAutoconfiguration(BatchActionFormInterface::class)
            ->addTag('ergonode.batch_action.form_provider');
        $container
            ->registerForAutoconfiguration(BatchActionReprocessFormInterface::class)
            ->addTag('ergonode.batch_action.reprocessing_form_provider');

        $configuration = $this->processConfiguration(new Configuration(), $configs);

        if (!$configuration['test']) {
            return;
        }

        $loader->load('test.yaml');
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        $this->prependNelmioApiDoc($container);
        $this->prependMessenger($container);
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
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));

        $loader->load('messenger.yaml');
    }
}
