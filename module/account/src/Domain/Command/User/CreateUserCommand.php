<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Command\User;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

/**
 */
class CreateUserCommand implements DomainCommandInterface
{
    /**
     * @var UserId
     */
    private UserId $id;

    /**
     * @var MultimediaId|null
     */
    private ?MultimediaId $avatarId;

    /**
     * @var string
     */
    private string $firstName;

    /**
     * @var string
     */
    private string $lastName;

    /**
     * @var Email
     */
    private Email $email;

    /**
     * @var Password
     */
    private Password $password;

    /**
     * @var Language
     */
    private Language $language;

    /**
     * @var RoleId
     */
    private RoleId $roleId;

    /**
     * @var bool
     */
    private bool $isActive;

    /**
     * @param string            $firstName
     * @param string            $lastName
     * @param Email             $email
     * @param Language          $language
     * @param Password          $password
     * @param RoleId            $roleId
     * @param bool              $isActive
     * @param MultimediaId|null $avatarId
     *
     * @throws \Exception
     */
    public function __construct(
        string $firstName,
        string $lastName,
        Email $email,
        Language $language,
        Password $password,
        RoleId $roleId,
        bool $isActive = true,
        ?MultimediaId $avatarId = null
    ) {
        $this->id = UserId::generate();
        $this->avatarId = $avatarId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->language = $language;
        $this->roleId = $roleId;
        $this->isActive = $isActive;
    }

    /**
     * @return UserId
     */
    public function getId(): UserId
    {
        return $this->id;
    }

    /**
     * @return MultimediaId|null
     */
    public function getAvatarId(): ?MultimediaId
    {
        return $this->avatarId;
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
     * @return Password
     */
    public function getPassword(): Password
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
     * @return RoleId
     */
    public function getRoleId(): RoleId
    {
        return $this->roleId;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }
}
