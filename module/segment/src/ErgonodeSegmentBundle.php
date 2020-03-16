<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment;

use Ergonode\Condition\Application\DependencyInjection\CompilerPass\ProvideConditionDictionaryCompilerPass;
use Ergonode\Core\Application\AbstractModule;
use Ergonode\Segment\Application\DependencyInjection\ErgonodeSegmentExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 *
 */
class ErgonodeSegmentBundle extends AbstractModule
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $compiler = new ProvideConditionDictionaryCompilerPass(
            ErgonodeSegmentExtension::CONDITION_GROUP_NAME,
            ErgonodeSegmentExtension::CONDITION_PARAMETER_NAME
        );

        $container->addCompilerPass($compiler);
    }
}
