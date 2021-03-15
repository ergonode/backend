<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Test\Behat\Service;

use Behatch\HttpCall\Request;
use Ergonode\Core\Test\Behat\Service\RequestAuthenticatorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class RequestAuthenticator implements RequestAuthenticatorInterface
{
    private JWTTokenManagerInterface $JWTTokenManager;
    private string $authorizationHeader;

    public function __construct(JWTTokenManagerInterface $JWTTokenManager, string $authorizationHeader)
    {
        $this->JWTTokenManager = $JWTTokenManager;
        $this->authorizationHeader = $authorizationHeader;
    }

    public function authenticate(Request $request, UserInterface $user): void
    {
        $token = $this->JWTTokenManager->create($user);

        /** @phpstan-ignore-next-line */
        $request->setHttpHeader(
            $this->authorizationHeader,
            "Bearer $token",
        );
    }
}
