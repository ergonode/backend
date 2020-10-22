<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Application\DependencyInjection;

use Ergonode\Category\Application\Form\CategoryFormInterface;
use Ergonode\Category\Domain\Entity\CategoryInterface;
use Ergonode\Category\Infrastructure\Factory\Command\CreateCategoryCommandFactoryInterface;
use Ergonode\Category\Infrastructure\Factory\Command\UpdateCategoryCommandFactoryInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ErgonodeCategoryExtension extends Extension
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
}
