<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\LanguagePrivileges;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;

class UserCreatedEvent implements AggregateEventInterface
{
    private UserId $id;

    private string $firstName;

    private string $lastName;

    private Email $email;

    private Password $password;

    private Language $language;

    private RoleId $roleId;

    /**
     * @var LanguagePrivileges[]
     */
    private array $languagePrivilegesCollection;

    private bool $isActive;

    /**
     * @param LanguagePrivileges[] $languagePrivilegesCollection
     */
    public function __construct(
        UserId $id,
        string $firstName,
        string $lastName,
        Email $email,
        Language $language,
        Password $password,
        RoleId $roleId,
        array $languagePrivilegesCollection,
        bool $isActive = true
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->language = $language;
        $this->roleId = $roleId;
        $this->languagePrivilegesCollection = $languagePrivilegesCollection;
        $this->isActive = $isActive;
    }

    public function getAggregateId(): UserId
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

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getRoleId(): RoleId
    {
        return $this->roleId;
    }

    /**
     * @return LanguagePrivileges[]
     */
    public function getLanguagePrivilegesCollection(): array
    {
        return $this->languagePrivilegesCollection;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
