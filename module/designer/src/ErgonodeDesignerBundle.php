<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer;

use Ergonode\SharedKernel\Application\AbstractModule;
use Ergonode\Designer\Application\DependencyInjection\CompilerPass\TemplateElementProviderCompilerPass;
use Ergonode\Designer\Application\DependencyInjection\CompilerPass\TemplateGeneratorStrategyCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ErgonodeDesignerBundle extends AbstractModule
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new TemplateGeneratorStrategyCompilerPass());
        $container->addCompilerPass(new TemplateElementProviderCompilerPass());
    }
}
