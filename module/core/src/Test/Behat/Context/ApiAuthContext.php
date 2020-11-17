<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\Behat\Context\Context;
use Behatch\HttpCall\Request;
use Ergonode\Authentication\Application\Security\User\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class ApiAuthContext implements Context
{
    private JWTTokenManagerInterface $JWTTokenManager;

    private Request $request;

    public function __construct(
        JWTTokenManagerInterface $JWTTokenManager,
        Request $request
    ) {
        $this->JWTTokenManager = $JWTTokenManager;
        $this->request         = $request;
    }

    /**
     * @Given I am Authenticated as :user
     */
    public function iAmAuthenticatedAsUser(User $user): void
    {
        $token = $this->JWTTokenManager->create($user);
        /** @phpstan-ignore-next-line */
        $this->request->setHttpHeader('JWTAuthorization', 'Bearer '.$token);
    }
}
