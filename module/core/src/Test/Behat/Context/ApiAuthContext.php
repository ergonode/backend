<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\Behat\Context\Context;
use Behatch\HttpCall\Request;
use Ergonode\Authentication\Application\Security\User\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

/**
 */
class ApiAuthContext implements Context
{
    /**
     * @var JWTTokenManagerInterface
     */
    private JWTTokenManagerInterface $JWTTokenManager;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * @param JWTTokenManagerInterface $JWTTokenManager
     * @param Request                  $request
     */
    public function __construct(
        JWTTokenManagerInterface $JWTTokenManager,
        Request $request
    ) {
        $this->JWTTokenManager = $JWTTokenManager;
        $this->request         = $request;
    }

    /**
     * @Given I am Authenticated as :user
     *
     * @param User $user
     */
    public function iAmAuthenticatedAsUser(User $user): void
    {
        $token = $this->JWTTokenManager->create($user);
        $this->request->setHttpHeader('JWTAuthorization', 'Bearer '.$token);
    }
}
