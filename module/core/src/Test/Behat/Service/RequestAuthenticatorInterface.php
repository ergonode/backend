<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Service;

use Behatch\HttpCall\Request;
use Symfony\Component\Security\Core\User\UserInterface;

interface RequestAuthenticatorInterface
{
    public function authenticate(Request $request, UserInterface $user): void;
}
