<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer;

use Ergonode\Core\Application\AbstractModule;
use Ergonode\Designer\Application\DependencyInjection\CompilerPass\TemplateGeneratorStrategyCompilerPass;
use Ergonode\Designer\Application\DependencyInjection\CompilerPass\TemplateRelationCheckerCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ErgonodeDesignerBundle
 */
class ErgonodeDesignerBundle extends AbstractModule
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new TemplateGeneratorStrategyCompilerPass());
        $container->addCompilerPass(new TemplateRelationCheckerCompilerPass());
    }
}
