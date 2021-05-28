<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\DependencyInjection;

use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintStrategyInterface;
use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Ergonode\Attribute\Infrastructure\Factory\Command\CreateAttributeCommandFactoryInterface;
use Ergonode\Attribute\Infrastructure\Factory\Command\UpdateAttributeCommandFactoryInterface;
use Ergonode\Attribute\Domain\Entity\AttributeInterface;
use Ergonode\Attribute\Application\Form\Attribute\AttributeFormInterface;
use Ergonode\Attribute\Infrastructure\Mapper\Strategy\AttributeMapperStrategyInterface;

class ErgonodeAttributeExtension extends Extension implements PrependExtensionInterface
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
            ->registerForAutoconfiguration(AttributeInterface::class)
            ->addTag(CompilerPass\AttributeTypeCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(AttributeFormInterface::class)
            ->addTag(CompilerPass\AttributeFormCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(CreateAttributeCommandFactoryInterface::class)
            ->addTag(CompilerPass\CreateAttributeCommandFactoryProviderInterfaceCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(UpdateAttributeCommandFactoryInterface::class)
            ->addTag(CompilerPass\UpdateAttributeCommandFactoryProviderInterfaceCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(AttributeValueConstraintStrategyInterface::class)
            ->addTag(CompilerPass\AttributeValueConstraintStrategyInterfaceCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(AttributeMapperStrategyInterface::class)
            ->addTag('attribute.attribute_mapper_strategy');

        $loader->load('services.yml');
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        if (!in_array(NelmioApiDocBundle::class, $container->getParameter('kernel.bundles'), true)) {
            return;
        }
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));

        $loader->load('nelmio_api_doc.yaml');
    }
}
