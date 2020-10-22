<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Command\User;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

class ChangeUserPasswordCommand implements DomainCommandInterface
{
    /**
     * @var UserId
     */
    private UserId $id;

    /**
     * @var Password
     */
    private Password $password;

    /**
     * @param UserId   $id
     * @param Password $password
     */
    public function __construct(UserId $id, Password $password)
    {
        $this->id = $id;
        $this->password = $password;
    }

    /**
     * @return UserId
     */
    public function getId(): UserId
    {
        return $this->id;
    }

    /**
     * @return Password
     */
    public function getPassword(): Password
    {
        return $this->password;
    }
}
