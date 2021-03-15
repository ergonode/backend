<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\DependencyInjection;

use Gesdinet\JWTRefreshTokenBundle\GesdinetJWTRefreshTokenBundle;
use Nelmio\ApiDocBundle\NelmioApiDocBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ErgonodeAuthenticationExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @param array $configs
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../Resources/config')
        );

        $loader->load('services.yml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('ergonode.authentication.doctrine_orm_disabled', $config['doctrine_orm_disabled']);
        if (!$config['doctrine_orm_disabled']) {
            return;
        }

        $loader->load('refresh_token_without_orm.yaml');
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        $this->prependNelmioApiDoc($container);
        $this->prependGesdinetJWTRefreshToken($container);
    }

    private function prependNelmioApiDoc(ContainerBuilder $container): void
    {
        if (!in_array(NelmioApiDocBundle::class, $container->getParameter('kernel.bundles'), true)) {
            return;
        }
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));

        $loader->load('nelmio_api_doc.yaml');
    }

    private function prependGesdinetJWTRefreshToken(ContainerBuilder $container): void
    {
        if (!in_array(GesdinetJWTRefreshTokenBundle::class, $container->getParameter('kernel.bundles'), true)) {
            return;
        }
        $configs = $container->getExtensionConfig($this->getAlias());
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        if (!$config['doctrine_orm_disabled']) {
            return;
        }
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../Resources/config'));

        $loader->load('gesdinet_jwt_refresh_token.yaml');
    }
}
