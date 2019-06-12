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
     * @param UserId            $id
     * @param string            $firstName
     * @param string            $lastName
     * @param string            $email
     * @param Language          $language
     * @param Password          $password
     * @param MultimediaId|null $avatarId
     */
    public function __construct(UserId $id, string $firstName, string $lastName, string $email, Language $language, Password $password, MultimediaId $avatarId = null)
    {
        $this->apply(new UserCreatedEvent($id, $firstName, $lastName, $email, $language, $password, $avatarId));
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
            $this->apply(new UserFirstNameChangedEvent($firstName));
        }
    }

    /**
     * @param string $lastName
     */
    public function changeLastName(string $lastName): void
    {
        if ($this->lastName !== $lastName) {
            $this->apply(new UserLastNameChangedEvent($lastName));
        }
    }

    /**
     * @param Language $language
     */
    public function changeLanguage(Language $language): void
    {
        if ($this->language->getCode() !== $language->getCode()) {
            $this->apply(new UserLanguageChangedEvent($language));
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
    }

    /**
     * @param UserAvatarChangedEvent $event
     */
    protected function applyUserAvatarChangedEvent(UserAvatarChangedEvent $event): void
    {
        $this->avatarId = $event->getAvatarId();
    }

    /**
     * @param UserFirstNameChangedEvent $event
     */
    protected function applyUserFirstNameChangedEvent(UserFirstNameChangedEvent $event): void
    {
        $this->firstName = $event->getFirstName();
    }

    /**
     * @param UserLastNameChangedEvent $event
     */
    protected function applyUserLastNameChangedEvent(UserLastNameChangedEvent $event): void
    {
        $this->lastName = $event->getLastName();
    }

    /**
     * @param UserLanguageChangedEvent $event
     */
    protected function applyUserLanguageChangedEvent(UserLanguageChangedEvent $event): void
    {
        $this->language = $event->getLanguage();
    }

    /**
     * @param UserPasswordChangedEvent $event
     */
    protected function applyUserPasswordChangedEvent(UserPasswordChangedEvent $event): void
    {
    }
}
