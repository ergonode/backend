<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Application\DependencyInjection\CompilerPass;

use Ergonode\Condition\Infrastructure\JMS\Serializer\Handler\ConditionInterfaceHandler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\ExpressionLanguage\Expression;

/**
 */
class ConditionInterfaceHandlerCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $handlerServiceDefinition = $container->getDefinition(ConditionInterfaceHandler::class);
        $servicesIds = $container->findTaggedServiceIds(ConditionConstraintCompilerPass::TAG);
        foreach (array_keys($servicesIds) as $serviceId) {
            $callArgument = sprintf('service("%s").getValidatedClass()', addslashes($serviceId));
            $expression = new Expression($callArgument);
            $handlerServiceDefinition
                ->addMethodCall(
                    'set',
                    [$expression]
                );
        }
    }
}
