<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\Account\Domain\ValueObject\LanguagePrivileges;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AvatarId;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UserCreatedEvent implements DomainEventInterface
{
    /**
     * @var UserId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UserId")
     */
    private UserId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $firstName;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $lastName;

    /**
     * @var Email
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\ValueObject\Email")
     */
    private Email $email;

    /**
     * @var Password
     *
     * @JMS\Type("Ergonode\Account\Domain\ValueObject\Password")
     */
    private Password $password;

    /**
     * @var Language
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private Language $language;

    /**
     * @var AvatarId|null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AvatarId")
     */
    private ?AvatarId $avatarId;

    /**
     * @var RoleId
     *
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
     * @var bool
     *
     * @JMS\Type("boolean")
     */
    private bool $isActive;

    /**
     * @param UserId               $id
     * @param string               $firstName
     * @param string               $lastName
     * @param Email                $email
     * @param Language             $language
     * @param Password             $password
     * @param RoleId               $roleId
     * @param LanguagePrivileges[] $languagePrivilegesCollection
     * @param bool                 $isActive
     * @param AvatarId|null        $avatarId
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
        bool $isActive = true,
        ?AvatarId $avatarId = null
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->language = $language;
        $this->roleId = $roleId;
        $this->languagePrivilegesCollection = $languagePrivilegesCollection;
        $this->avatarId = $avatarId;
        $this->isActive = $isActive;
    }

    /**
     * @return UserId
     */
    public function getAggregateId(): UserId
    {
        return $this->id;
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
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @return Password
     */
    public function getPassword(): Password
    {
        return $this->password;
    }

    /**
     * @return AvatarId|null
     */
    public function getAvatarId(): ?AvatarId
    {
        return $this->avatarId;
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
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }
}
