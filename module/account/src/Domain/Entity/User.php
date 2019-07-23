<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Entity;

use Ergonode\Account\Domain\Event\UserAvatarChangedEvent;
use Ergonode\Account\Domain\Event\UserCreatedEvent;
use Ergonode\Account\Domain\Event\UserFirstNameChangedEvent;
use Ergonode\Account\Domain\Event\UserLanguageChangedEvent;
use Ergonode\Account\Domain\Event\UserLastNameChangedEvent;
use Ergonode\Account\Domain\Event\UserPasswordChangedEvent;
use Ergonode\Account\Domain\Event\UserRoleChangedEvent;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;

/**
 */
class User extends AbstractAggregateRoot
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
     * @var string
     */
    private $email;

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
     * @param UserId            $id
     * @param string            $firstName
     * @param string            $lastName
     * @param string            $email
     * @param Language          $language
     * @param Password          $password
     * @param RoleId            $roleId
     * @param MultimediaId|null $avatarId
     */
    public function __construct(
        UserId $id,
        string $firstName,
        string $lastName,
        string $email,
        Language $language,
        Password $password,
        RoleId $roleId,
        MultimediaId $avatarId = null
    ) {
        $this->apply(new UserCreatedEvent($id, $firstName, $lastName, $email, $language, $password, $roleId, $avatarId));
    }

    /**
     * @return AbstractId|UserId
     */
    public function getId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
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
     * @param string $firstName
     */
    public function changeFirstName(string $firstName): void
    {
        if ($this->firstName !== $firstName) {
            $this->apply(new UserFirstNameChangedEvent($this->firstName, $firstName));
        }
    }

    /**
     * @param RoleId $roleId
     */
    public function changeRole(RoleId $roleId): void
    {
        if (!$roleId->isEqual($this->roleId)) {
            $this->apply(new UserRoleChangedEvent($this->roleId, $roleId));
        }
    }

    /**
     * @param string $lastName
     */
    public function changeLastName(string $lastName): void
    {
        if ($this->lastName !== $lastName) {
            $this->apply(new UserLastNameChangedEvent($this->lastName, $lastName));
        }
    }

    /**
     * @param Language $language
     */
    public function changeLanguage(Language $language): void
    {
        if (!$language->isEqual($this->language)) {
            $this->apply(new UserLanguageChangedEvent($this->language, $language));
        }
    }

    /**
     * @param MultimediaId|null $avatarId
     */
    public function changeAvatar(MultimediaId $avatarId = null): void
    {
        $this->apply(new UserAvatarChangedEvent($avatarId));
    }

    /**
     * @param Password $password
     */
    public function changePassword(Password $password): void
    {
        $this->apply(new UserPasswordChangedEvent($password));
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
        $this->avatarId = $event->getAvatarId();
        $this->roleId = $event->getRoleId();
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
    }
}
