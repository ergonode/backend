<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Command\User;

use Ergonode\Account\Domain\Command\AccountCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Account\Domain\ValueObject\Password;

class ChangeUserPasswordCommand implements AccountCommandInterface
{
    private UserId $id;

    private Password $password;

    public function __construct(UserId $id, Password $password)
    {
        $this->id = $id;
        $this->password = $password;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }
}
