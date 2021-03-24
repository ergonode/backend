<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Entity;

use Ergonode\Account\Domain\Event\User\UserActivatedEvent;
use Ergonode\Account\Domain\Event\User\UserAvatarChangedEvent;
use Ergonode\Account\Domain\Event\User\UserAvatarDeletedEvent;
use Ergonode\Account\Domain\Event\User\UserCreatedEvent;
use Ergonode\Account\Domain\Event\User\UserDeactivatedEvent;
use Ergonode\Account\Domain\Event\User\UserFirstNameChangedEvent;
use Ergonode\Account\Domain\Event\User\UserLanguageChangedEvent;
use Ergonode\Account\Domain\Event\User\UserLanguagePrivilegesCollectionChangedEvent;
use Ergonode\Account\Domain\Event\User\UserLastNameChangedEvent;
use Ergonode\Account\Domain\Event\User\UserPasswordChangedEvent;
use Ergonode\Account\Domain\Event\User\UserRoleChangedEvent;
use Ergonode\Core\Domain\User\LanguageCollectionAwareInterface;
use Ergonode\Core\Domain\User\UserInterface;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\LanguagePrivileges;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;

class User extends AbstractAggregateRoot implements UserInterface, LanguageCollectionAwareInterface
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
        array $languagePrivilegesCollection,
        bool $isActive = true
    ) {
        $this->apply(
            new UserCreatedEvent(
                $id,
                $firstName,
                $lastName,
                $email,
                $language,
                $password,
                $roleId,
                $languagePrivilegesCollection,
                $isActive,
            )
        );
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

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

    /**
     * @throws \Exception
     */
    public function changeFirstName(string $firstName): void
    {
        if ($this->firstName !== $firstName) {
            $this->apply(new UserFirstNameChangedEvent($this->id, $firstName));
        }
    }

    /**
     * @throws \Exception
     */
    public function changeRole(RoleId $roleId): void
    {
        if (!$roleId->isEqual($this->roleId)) {
            $this->apply(new UserRoleChangedEvent($this->id, $roleId));
        }
    }

    /**
     * @param array $languagePrivilegesCollection
     *
     * @throws \Exception
     */
    public function changeLanguagePrivilegesCollection(array $languagePrivilegesCollection): void
    {
        if (count(array_diff_key($languagePrivilegesCollection, $this->languagePrivilegesCollection)) === 0
            && count(array_diff_key($this->languagePrivilegesCollection, $languagePrivilegesCollection)) === 0) {
            $elementNotEqual = false;
            foreach ($languagePrivilegesCollection as $languageCode => $languagePrivileges) {
                if (!$this->languagePrivilegesCollection[$languageCode]->isEqual($languagePrivileges)) {
                    $elementNotEqual = true;
                    break;
                }
            }
            if (!$elementNotEqual) {
                return;
            }
        }
        $this->apply(
            new UserLanguagePrivilegesCollectionChangedEvent(
                $this->id,
                $languagePrivilegesCollection
            )
        );
    }

    /**
     * @throws \Exception
     */
    public function changeLastName(string $lastName): void
    {
        if ($this->lastName !== $lastName) {
            $this->apply(new UserLastNameChangedEvent($this->id, $lastName));
        }
    }

    /**
     * @throws \Exception
     */
    public function changeLanguage(Language $language): void
    {
        if (!$language->isEqual($this->language)) {
            $this->apply(new UserLanguageChangedEvent($this->id, $language));
        }
    }

    /**
     * @throws \Exception
     */
    public function changeAvatar(string $avatarFilename = null): void
    {
        $this->apply(new UserAvatarChangedEvent($this->id, $avatarFilename));
    }

    /**
     * @throws \Exception
     */
    public function changePassword(Password $password): void
    {
        $this->apply(new UserPasswordChangedEvent($this->id, $password));
    }

    /**
     * @throws \Exception
     */
    public function removeAvatar(): void
    {
        $this->apply(new UserAvatarDeletedEvent($this->id));
    }

    /**
     * @throws \Exception
     */
    public function activate(): void
    {
        if ($this->isActive()) {
            throw new \LogicException('User already activated');
        }

        $this->apply(new UserActivatedEvent($this->id));
    }

    /**
     * @throws \Exception
     */
    public function deactivate(): void
    {
        if (!$this->isActive()) {
            throw new \LogicException('User already deactivated');
        }

        $this->apply(new UserDeactivatedEvent($this->id));
    }

    public function getSalt(): string
    {
        return '';
    }

    public function getUsername(): string
    {
        return $this->email->getValue();
    }

    public function eraseCredentials(): bool
    {
        return false;
    }

    public function hasReadLanguagePrivilege(Language $language): bool
    {
        return (
            isset($this->languagePrivilegesCollection[$language->getCode()])
            && $this->languagePrivilegesCollection[$language->getCode()]->isReadable()
        );
    }

    public function hasEditLanguagePrivilege(Language $language): bool
    {
        return (
            isset($this->languagePrivilegesCollection[$language->getCode()])
            && $this->languagePrivilegesCollection[$language->getCode()]->isEditable()
        );
    }

    protected function applyUserCreatedEvent(UserCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->firstName = $event->getFirstName();
        $this->lastName = $event->getLastName();
        $this->email = $event->getEmail();
        $this->language = $event->getLanguage();
        $this->password = $event->getPassword();
        $this->roleId = $event->getRoleId();
        $this->languagePrivilegesCollection = $event->getLanguagePrivilegesCollection();
        $this->isActive = $event->isActive();
    }

    protected function applyUserLanguagePrivilegesCollectionChangedEvent(
        UserLanguagePrivilegesCollectionChangedEvent $event
    ): void {
        $this->languagePrivilegesCollection = $event->getTo();
    }

    protected function applyUserRoleChangedEvent(UserRoleChangedEvent $event): void
    {
        $this->roleId = $event->getTo();
    }

    protected function applyUserFirstNameChangedEvent(UserFirstNameChangedEvent $event): void
    {
        $this->firstName = $event->getTo();
    }

    protected function applyUserLastNameChangedEvent(UserLastNameChangedEvent $event): void
    {
        $this->lastName = $event->getTo();
    }

    protected function applyUserLanguageChangedEvent(UserLanguageChangedEvent $event): void
    {
        $this->language = $event->getTo();
    }

    protected function applyUserPasswordChangedEvent(UserPasswordChangedEvent $event): void
    {
        $this->password = $event->getPassword();
    }

    protected function applyUserActivatedEvent(UserActivatedEvent $event): void
    {
        $this->isActive = true;
    }

    protected function applyUserDeactivatedEvent(UserDeactivatedEvent $event): void
    {
        $this->isActive = false;
    }
}
