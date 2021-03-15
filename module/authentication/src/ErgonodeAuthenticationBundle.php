<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication;

use Ergonode\Authentication\Application\DependencyInjection\Compiler\JWTManagerPass;
use Ergonode\Authentication\Application\DependencyInjection\Compiler\UniqueRefreshTokenValidatorPass;
use Ergonode\SharedKernel\Application\AbstractModule;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ErgonodeAuthenticationBundle extends AbstractModule
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container
            ->addCompilerPass(new JWTManagerPass())
            ->addCompilerPass(new UniqueRefreshTokenValidatorPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 1000)
        ;
    }
}
