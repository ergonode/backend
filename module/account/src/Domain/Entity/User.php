<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Entity;

use Ergonode\Account\Domain\Event\User\UserActivatedEvent;
use Ergonode\Account\Domain\Event\User\UserAvatarChangedEvent;
use Ergonode\Account\Domain\Event\User\UserCreatedEvent;
use Ergonode\Account\Domain\Event\User\UserDeactivatedEvent;
use Ergonode\Account\Domain\Event\User\UserFirstNameChangedEvent;
use Ergonode\Account\Domain\Event\User\UserLanguageChangedEvent;
use Ergonode\Account\Domain\Event\User\UserLastNameChangedEvent;
use Ergonode\Account\Domain\Event\User\UserPasswordChangedEvent;
use Ergonode\Account\Domain\Event\User\UserRoleChangedEvent;
use Ergonode\Account\Domain\ValueObject\Email;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 */
class User extends AbstractAggregateRoot implements UserInterface
{
    /**
     * @var UserId
     */
    private $id;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var Email
     */
    private $email;

    /**
     * @var Password
     */
    private $password;

    /**
     * @var Language
     */
    private $language;

    /**
     * @var MultimediaId
     */
    private $avatarId;

    /**
     * @var RoleId
     */
    private $roleId;

    /**
     * @var bool
     */
    private $isActive;

    /**
     * @param UserId            $id
     * @param string            $firstName
     * @param string            $lastName
     * @param Email             $email
     * @param Language          $language
     * @param Password          $password
     * @param RoleId            $roleId
     * @param MultimediaId|null $avatarId
     * @param bool              $isActive
     *
     * @throws \Exception
     */
    public function __construct(
        UserId $id,
        string $firstName,
        string $lastName,
        Email $email,
        Language $language,
        Password $password,
        RoleId $roleId,
        ?MultimediaId $avatarId = null,
        bool $isActive = true
    ) {
        $this->apply(new UserCreatedEvent($id, $firstName, $lastName, $email, $language, $password, $roleId, $isActive, $avatarId));
    }

    /**
     * @return AbstractId|UserId
     */
    public function getId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
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
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password->getValue();
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return [];
    }

    /**
     * @return RoleId
     */
    public function getRoleId(): RoleId
    {
        return $this->roleId;
    }

    /**
     * @return MultimediaId|null
     */
    public function getAvatarId(): ?MultimediaId
    {
        return $this->avatarId;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param string $firstName
     *
     * @throws \Exception
     */
    public function changeFirstName(string $firstName): void
    {
        if ($this->firstName !== $firstName) {
            $this->apply(new UserFirstNameChangedEvent($this->firstName, $firstName));
        }
    }

    /**
     * @param RoleId $roleId
     *
     * @throws \Exception
     */
    public function changeRole(RoleId $roleId): void
    {
        if (!$roleId->isEqual($this->roleId)) {
            $this->apply(new UserRoleChangedEvent($this->roleId, $roleId));
        }
    }

    /**
     * @param string $lastName
     *
     * @throws \Exception
     */
    public function changeLastName(string $lastName): void
    {
        if ($this->lastName !== $lastName) {
            $this->apply(new UserLastNameChangedEvent($this->lastName, $lastName));
        }
    }

    /**
     * @param Language $language
     *
     * @throws \Exception
     */
    public function changeLanguage(Language $language): void
    {
        if (!$language->isEqual($this->language)) {
            $this->apply(new UserLanguageChangedEvent($this->language, $language));
        }
    }

    /**
     * @param MultimediaId|null $avatarId
     *
     * @throws \Exception
     */
    public function changeAvatar(MultimediaId $avatarId = null): void
    {
        $this->apply(new UserAvatarChangedEvent($avatarId));
    }

    /**
     * @param Password $password
     *
     * @throws \Exception
     */
    public function changePassword(Password $password): void
    {
        $this->apply(new UserPasswordChangedEvent($password));
    }

    /**
     * @throws \Exception
     */
    public function activate(): void
    {
        if ($this->isActive()) {
            throw new \LogicException('User already activated');
        }

        $this->apply(new UserActivatedEvent());
    }

    /**
     * @throws \Exception
     */
    public function deactivate(): void
    {
        if (!$this->isActive()) {
            throw new \LogicException('User already deactivated');
        }

        $this->apply(new UserDeactivatedEvent());
    }

    /**
     * @return string
     */
    public function getSalt(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->email->getValue();
    }

    /**
     * @return bool
     */
    public function eraseCredentials(): bool
    {
        return false;
    }

    /**
     * @param UserCreatedEvent $event
     */
    protected function applyUserCreatedEvent(UserCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->firstName = $event->getFirstName();
        $this->lastName = $event->getLastName();
        $this->email = $event->getEmail();
        $this->language = $event->getLanguage();
        $this->password = $event->getPassword();
        $this->avatarId = $event->getAvatarId();
        $this->roleId = $event->getRoleId();
        $this->isActive = $event->isActive();
    }

    /**
     * @param UserAvatarChangedEvent $event
     */
    protected function applyUserAvatarChangedEvent(UserAvatarChangedEvent $event): void
    {
        $this->avatarId = $event->getAvatarId();
    }

    /**
     * @param UserRoleChangedEvent $event
     */
    protected function applyUserRoleChangedEvent(UserRoleChangedEvent $event): void
    {
        $this->roleId = $event->getTo();
    }

    /**
     * @param UserFirstNameChangedEvent $event
     */
    protected function applyUserFirstNameChangedEvent(UserFirstNameChangedEvent $event): void
    {
        $this->firstName = $event->getTo();
    }

    /**
     * @param UserLastNameChangedEvent $event
     */
    protected function applyUserLastNameChangedEvent(UserLastNameChangedEvent $event): void
    {
        $this->lastName = $event->getTo();
    }

    /**
     * @param UserLanguageChangedEvent $event
     */
    protected function applyUserLanguageChangedEvent(UserLanguageChangedEvent $event): void
    {
        $this->language = $event->getTo();
    }

    /**
     * @param UserPasswordChangedEvent $event
     */
    protected function applyUserPasswordChangedEvent(UserPasswordChangedEvent $event): void
    {
        $this->password = $event->getPassword();
    }

    /**
     * @param UserActivatedEvent $event
     */
    protected function applyUserActivatedEvent(UserActivatedEvent $event): void
    {
        $this->isActive = $event->isActive();
    }

    /**
     * @param UserDeactivatedEvent $event
     */
    protected function applyUserDeactivatedEvent(UserDeactivatedEvent $event): void
    {
        $this->isActive = $event->isActive();
    }
}
