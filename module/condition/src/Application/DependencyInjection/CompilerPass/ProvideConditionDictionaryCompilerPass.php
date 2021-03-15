<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Application\DependencyInjection\CompilerPass;

use Ergonode\Condition\Domain\Provider\ConditionDictionaryProvider;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ProvideConditionDictionaryCompilerPass implements CompilerPassInterface
{
    private string $group;

    private string $sourceParameterName;

    public function __construct(string $group, string $sourceParameterName)
    {
        $this->group = $group;
        $this->sourceParameterName = $sourceParameterName;
    }

    public function process(ContainerBuilder $container): void
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
