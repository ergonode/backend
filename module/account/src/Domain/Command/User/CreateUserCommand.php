<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Command\User;

use Ergonode\Account\Domain\Command\AccountCommandInterface;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;

class CreateUserCommand implements AccountCommandInterface
{
    private UserId $id;

    private string $firstName;

    private string $lastName;

    private Email $email;

    private Password $password;

    private Language $language;

    private RoleId $roleId;

    private bool $isActive;

    public function __construct(
        string $firstName,
        string $lastName,
        Email $email,
        Language $language,
        Password $password,
        RoleId $roleId,
        bool $isActive = true
    ) {
        $this->id = UserId::generate();
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->language = $language;
        $this->roleId = $roleId;
        $this->isActive = $isActive;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function getRoleId(): RoleId
    {
        return $this->roleId;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
