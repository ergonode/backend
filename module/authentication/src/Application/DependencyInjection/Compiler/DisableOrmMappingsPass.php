<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\DependencyInjection\Compiler;

use Gesdinet\JWTRefreshTokenBundle\GesdinetJWTRefreshTokenBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Enforces disabling of Doctrine ORM mappings
 * @see \Gesdinet\JWTRefreshTokenBundle\DependencyInjection\Compiler\DoctrineMappingsCompilerPass::process
 */
class DisableOrmMappingsPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->getParameter('ergonode.authentication.doctrine_orm_disabled')
            || !in_array(GesdinetJWTRefreshTokenBundle::class, $container->getParameter('kernel.bundles'), true)) {
            return;
        }

        $config = [
            'manager_type' => 'mongodb',
        ];
        $container->prependExtensionConfig(
            'gesdinet_jwt_refresh_token',
            $config,
        );
    }
}
