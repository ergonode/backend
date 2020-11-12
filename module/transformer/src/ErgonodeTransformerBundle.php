<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Transformer;

use Ergonode\SharedKernel\Application\AbstractModule;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Ergonode\Transformer\Application\DependencyInjection\CompilerPass\ConverterMapperCompilerPass;
use Ergonode\Transformer\Application\DependencyInjection\CompilerPass\TransformerGeneratorProviderStrategyCompilerPass;
use Ergonode\Transformer\Application\DependencyInjection\CompilerPass\ConverterCompilerPass;

class ErgonodeTransformerBundle extends AbstractModule
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new TransformerGeneratorProviderStrategyCompilerPass());
        $container->addCompilerPass(new ConverterMapperCompilerPass());
        $container->addCompilerPass(new ConverterCompilerPass());
    }
}
