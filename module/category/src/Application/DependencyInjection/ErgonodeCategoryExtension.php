<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Application\DependencyInjection;

use Ergonode\Category\Application\Form\CategoryFormInterface;
use Ergonode\Category\Domain\Entity\CategoryInterface;
use Ergonode\Category\Infrastructure\Factory\Command\CreateCategoryCommandFactoryInterface;
use Ergonode\Category\Infrastructure\Factory\Command\UpdateCategoryCommandFactoryInterface;
use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ErgonodeCategoryExtension extends Extension implements PrependExtensionInterface
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
            ->registerForAutoconfiguration(CategoryInterface::class)
            ->addTag(CompilerPass\CategoryTypeCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(CategoryFormInterface::class)
            ->addTag(CompilerPass\CategoryFormCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(CreateCategoryCommandFactoryInterface::class)
            ->addTag(CompilerPass\CreateCategoryCommandFactoryProviderInterfaceCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(UpdateCategoryCommandFactoryInterface::class)
            ->addTag(CompilerPass\UpdateCategoryCommandFactoryProviderInterfaceCompilerPass::TAG);

        $loader->load('services.yml');
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
        $configs = $container->getExtensionConfig($this->getAlias());
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        if (!$this->isConfigEnabled($container, $config['messenger'])) {
            return;
        }

        $container->setParameter(
            'ergonode.category.messenger_transport_name',
            $config['messenger']['transport_name'],
        );

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));

        $loader->load('messenger.yaml');
    }
}
