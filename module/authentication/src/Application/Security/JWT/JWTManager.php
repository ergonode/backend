<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\Security\JWT;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager as BaseJWTManager;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTManager extends BaseJWTManager
{
    protected function addUserIdentityToPayload(UserInterface $user, array &$payload): void
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $id = $accessor->getValue($user, $this->userIdentityField);
        if ($id instanceof \Stringable || method_exists($id, '__toString')) {
            $id = (string) $id;
        }
        $payload[$this->userIdClaim ?: $this->userIdentityField] = $id;
    }
}
