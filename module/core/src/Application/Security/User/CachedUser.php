<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Security\User;

use Ergonode\Core\Domain\User\UserInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;

final class CachedUser implements UserInterface
{
    private UserId $id;
    private string $firstName;
    private string $lastName;
    private RoleId $roleId;
    private Email $email;
    private Language $language;

    public static function createFromUser(UserInterface $user): self
    {
        return new self(
            $user->getId(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getRoleId(),
            $user->getEmail(),
            $user->getLanguage(),
            $user->isActive(),
        );
    }

    public function __construct(
        UserId $id,
        string $firstName,
        string $lastName,
        RoleId $roleId,
        Email $email,
        Language $language,
        bool $active
    ) {
        if (!$active) {
            throw new \LogicException('User has to be active.');
        }
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->roleId = $roleId;
        $this->email = $email;
        $this->language = $language;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): UserId
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoleId(): RoleId
    {
        return $this->roleId;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->email->getValue();
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * {@inheritdoc}
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
    }
}
