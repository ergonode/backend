<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Application\DependencyInjection\CompilerPass;

use Ergonode\Condition\Domain\Provider\ConditionDictionaryProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 */
class ProvideConditionDictionaryCompilerPass implements CompilerPassInterface
{
    /**
     * @var string
     */
    private string $group;

    /**
     * @var string
     */
    private string $sourceParameterName;

    /**
     * @param string $group
     * @param string $sourceParameterName
     */
    public function __construct(string $group, string $sourceParameterName)
    {
        $this->group = $group;
        $this->sourceParameterName = $sourceParameterName;
    }

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition(ConditionDictionaryProvider::class);

        $definition->addMethodCall(
            'set',
            [
                $this->group,
                $container->getParameter($this->sourceParameterName),
            ]
        );
    }
}
