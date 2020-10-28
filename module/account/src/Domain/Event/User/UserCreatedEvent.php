<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\Account\Domain\ValueObject\LanguagePrivileges;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use JMS\Serializer\Annotation as JMS;

class UserCreatedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UserId")
     */
    private UserId $id;

    /**
     * @JMS\Type("string")
     */
    private string $firstName;

    /**
     * @JMS\Type("string")
     */
    private string $lastName;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\ValueObject\Email")
     */
    private Email $email;

    /**
     * @JMS\Type("Ergonode\Account\Domain\ValueObject\Password")
     */
    private Password $password;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private Language $language;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\RoleId")
     */
    private RoleId $roleId;

    /**
     * @var LanguagePrivileges[]
     *
     * @JMS\Type("array<string, Ergonode\Account\Domain\ValueObject\LanguagePrivileges>")
     */
    private array $languagePrivilegesCollection;

    /**
     * @JMS\Type("boolean")
     */
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
