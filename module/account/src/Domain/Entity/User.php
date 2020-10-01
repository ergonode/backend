<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

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
use Ergonode\Account\Domain\ValueObject\LanguagePrivileges;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Authentication\Application\Security\User\UserInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;
use JMS\Serializer\Annotation as JMS;

/**
 */
class User extends AbstractAggregateRoot implements UserInterface
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

    /**
     * @return UserId
     */
    public function getId(): UserId
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

    /**
     * @param string $firstName
     *
     * @throws \Exception
     */
    public function changeFirstName(string $firstName): void
    {
        if ($this->firstName !== $firstName) {
            $this->apply(new UserFirstNameChangedEvent($this->id, $this->firstName, $firstName));
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
            $this->apply(new UserRoleChangedEvent($this->id, $this->roleId, $roleId));
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
                $this->languagePrivilegesCollection,
                $languagePrivilegesCollection
            )
        );
    }

    /**
     * @param string $lastName
     *
     * @throws \Exception
     */
    public function changeLastName(string $lastName): void
    {
        if ($this->lastName !== $lastName) {
            $this->apply(new UserLastNameChangedEvent($this->id, $this->lastName, $lastName));
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
            $this->apply(new UserLanguageChangedEvent($this->id, $this->language, $language));
        }
    }

    /**
     * @param string|null $avatarFilename
     *
     * @throws \Exception
     */
    public function changeAvatar(string $avatarFilename = null): void
    {
        $this->apply(new UserAvatarChangedEvent($this->id, $avatarFilename));
    }

    /**
     * @param Password $password
     *
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
     * @param Language $language
     *
     * @return bool
     */
    public function hasReadLanguagePrivilege(Language $language): bool
    {
        return (
            isset($this->languagePrivilegesCollection[$language->getCode()])
            && $this->languagePrivilegesCollection[$language->getCode()]->isReadable()
        );
    }

    /**
     * @param Language $language
     *
     * @return bool
     */
    public function hasEditLanguagePrivilege(Language $language): bool
    {
        return (
            isset($this->languagePrivilegesCollection[$language->getCode()])
            && $this->languagePrivilegesCollection[$language->getCode()]->isEditable()
        );
    }

    /**
     * @param UserCreatedEvent $event
     */
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

    /**
     *
     * @param UserLanguagePrivilegesCollectionChangedEvent $event
     */
    protected function applyUserLanguagePrivilegesCollectionChangedEvent(
        UserLanguagePrivilegesCollectionChangedEvent $event
    ): void {
        $this->languagePrivilegesCollection = $event->getTo();
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
        $this->isActive = true;
    }

    /**
     * @param UserDeactivatedEvent $event
     */
    protected function applyUserDeactivatedEvent(UserDeactivatedEvent $event): void
    {
        $this->isActive = false;
    }
}
