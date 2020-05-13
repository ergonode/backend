<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\DependencyInjection;

use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintStrategyInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Ergonode\Attribute\Infrastructure\Factory\Command\CreateAttributeCommandFactoryInterface;
use Ergonode\Attribute\Infrastructure\Factory\Command\UpdateAttributeCommandFactoryInterface;
use Ergonode\Attribute\Domain\Entity\AttributeInterface;
use Ergonode\Attribute\Application\Form\Attribute\AttributeFormInterface;

/**
 */
class ErgonodeAttributeExtension extends Extension
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

        $loader->load('services.yml');
    }
}
