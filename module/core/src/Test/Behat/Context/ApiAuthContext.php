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
use Symfony\Component\Security\Core\User\UserInterface;

class ApiAuthContext implements Context
{
    private Request $request;
    private RequestAuthenticatorInterface $authenticator;

    public function __construct(Request $request, RequestAuthenticatorInterface $authenticator)
    {
        $this->request = $request;
        $this->authenticator = $authenticator;
    }

    /**
     * @Given I am Authenticated as :user
     */
    public function iAmAuthenticatedAsUser(UserInterface $user): void
    {
        $this->authenticator->authenticate($this->request, $user);
    }
}
