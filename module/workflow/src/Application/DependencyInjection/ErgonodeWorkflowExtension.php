<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Ergonode\Workflow\Application\Form\Workflow\WorkflowFormInterface;
use Ergonode\Workflow\Domain\Entity\WorkflowInterface;
use Ergonode\Workflow\Infrastructure\Factory\Command\CreateWorkflowCommandFactoryInterface;
use Ergonode\Workflow\Infrastructure\Factory\Command\UpdateWorkflowCommandFactoryInterface;

class ErgonodeWorkflowExtension extends Extension
{

    public const CONDITION_GROUP_NAME = 'workflow';
    public const CONDITION_PARAMETER_NAME = 'ergonode_workflow.conditions';

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

        $loader->load('services.yml');

        $container
            ->registerForAutoconfiguration(WorkflowInterface::class)
            ->addTag(CompilerPass\WorkflowTypeCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(WorkflowFormInterface::class)
            ->addTag(CompilerPass\WorkflowFormCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(CreateWorkflowCommandFactoryInterface::class)
            ->addTag(CompilerPass\CreateWorkflowCommandFactoryProviderInterfaceCompilerPass::TAG);

        $container
            ->registerForAutoconfiguration(UpdateWorkflowCommandFactoryInterface::class)
            ->addTag(CompilerPass\UpdateWorkflowCommandFactoryProviderInterfaceCompilerPass::TAG);

        $configuration = new Configuration();
        $processedConfig = $this->processConfiguration($configuration, $configs);

        $container->setParameter(self::CONDITION_PARAMETER_NAME, $processedConfig['conditions']);
    }
}
