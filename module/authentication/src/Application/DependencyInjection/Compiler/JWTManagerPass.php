<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\DependencyInjection\Compiler;

use Ergonode\Authentication\Application\Security\JWT\JWTManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class JWTManagerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('lexik_jwt_authentication.jwt_manager')) {
            return;
        }
        $container
            ->getDefinition('lexik_jwt_authentication.jwt_manager')
            ->setClass(JWTManager::class);
    }
}
