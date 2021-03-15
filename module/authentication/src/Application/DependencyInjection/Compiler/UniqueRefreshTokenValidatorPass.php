<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\DependencyInjection\Compiler;

use Ergonode\Authentication\Application\RefreshToken\Doctrine\RefreshTokenRepositoryInterface;
use Ergonode\Authentication\Application\Validator\UniqueRefreshTokenValidator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class UniqueRefreshTokenValidatorPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasDefinition('doctrine.orm.validator.unique')
            || $container->hasAlias('doctrine.orm.validator.unique')
        ) {
            return;
        }

        $validator = new Definition(
            UniqueRefreshTokenValidator::class,
            [
                new Reference(RefreshTokenRepositoryInterface::class),
            ],
        );
        $validator->addTag('validator.constraint_validator', ['alias' => 'doctrine.orm.validator.unique']);

        $container->setDefinition(
            'doctrine.orm.validator.unique',
            $validator,
        );
    }
}
