<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Application\DependencyInjection;

use Ergonode\Designer\Application\DependencyInjection\CompilerPass\TemplateElementProviderCompilerPass;
use Ergonode\Designer\Application\DependencyInjection\CompilerPass\TemplateGeneratorStrategyCompilerPass;
use Ergonode\Designer\Domain\Builder\BuilderTemplateElementStrategyInterface;
use Ergonode\Designer\Infrastructure\Generator\TemplateGeneratorInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ErgonodeDesignerExtension extends Extension
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
            ->registerForAutoconfiguration(TemplateGeneratorInterface::class)
            ->addTag(TemplateGeneratorStrategyCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(BuilderTemplateElementStrategyInterface::class)
            ->addTag(TemplateElementProviderCompilerPass::TAG);

        $loader->load('services.yml');
    }
}
