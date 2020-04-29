<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Command\User;

use Ergonode\Account\Domain\ValueObject\LanguagePrivileges;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

/**
 */
class UpdateUserCommand implements DomainCommandInterface
{
    /**
     * @var UserId
     */
    private UserId $id;

    /**
     * @var string
     */
    private string $firstName;

    /**
     * @var string
     */
    private string $lastName;

    /**
     * @var Password|null
     */
    private ?Password $password;

    /**
     * @var Language
     */
    private Language $language;

    /**
     * @var RoleId
     */
    private RoleId $roleId;

    /**
     * @var LanguagePrivileges[]
     */
    private array $languagePrivilegesCollection;

    /**
     * @var bool
     */
    private bool $isActive;

    /**
     * @param UserId               $id
     * @param string               $firstName
     * @param string               $lastName
     * @param Language             $language
     * @param RoleId               $roleId
     * @param LanguagePrivileges[] $languagePrivilegesCollection
     * @param bool                 $isActive
     * @param Password|null        $password
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

    /**
     * @return UserId
     */
    public function getId(): UserId
    {
        return $this->id;
    }

    /**
     * @return RoleId
     */
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

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return Password|null
     */
    public function getPassword(): ?Password
    {
        return $this->password;
    }

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }
}
