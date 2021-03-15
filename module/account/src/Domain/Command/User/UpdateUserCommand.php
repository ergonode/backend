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
use Ergonode\Core\Domain\ValueObject\LanguagePrivileges;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

class UpdateUserCommand implements AccountCommandInterface
{
    private UserId $id;

    private string $firstName;

    private string $lastName;

    private ?Password $password;

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
        Language $language,
        RoleId $roleId,
        array $languagePrivilegesCollection,
        bool $isActive,
        ?Password $password = null
    ) {
        $this->id = $id;
        $this->roleId = $roleId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->language = $language;
        $this->isActive = $isActive;
        $this->languagePrivilegesCollection = $languagePrivilegesCollection;
        $this->password = $password;
    }

    public function getId(): UserId
    {
        return $this->id;
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

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getPassword(): ?Password
    {
        return $this->password;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
