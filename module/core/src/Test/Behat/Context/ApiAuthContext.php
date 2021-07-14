<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\Behat\Context\Context;
use Behatch\HttpCall\Request;
use Ergonode\Core\Test\Behat\Service\RequestAuthenticatorInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiAuthContext implements Context
{
    private RequestAuthenticatorInterface $authenticator;
    private ContainerInterface $container;

    public function __construct(RequestAuthenticatorInterface $authenticator, ContainerInterface $container)
    {
        $this->authenticator = $authenticator;
        $this->container = $container;
    }

    /**
     * @Given I am Authenticated as :user
     */
    public function iAmAuthenticatedAsUser(UserInterface $user): void
    {
        /** @var Request $request */
        $request = $this->container
            ->get('behat.service_container')
            ->get('behatch.http_call.request');

        $this->authenticator->authenticate($request, $user);
    }
}
