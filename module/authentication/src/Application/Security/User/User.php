<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\Security\User;

use Ergonode\Core\Domain\User\UserInterface;

class User implements UserInterface
{
    /**
     * Allows easy serialization into token.
     */
    private string $id;
    private string $password;
    private array $roles;
    private bool $active;

    /**
     * @param array $roles
     */
    public function __construct(
        string $id,
        string $password,
        array $roles,
        bool $active
    ) {
        $this->id = $id;
        $this->password = $password;
        $this->roles = $roles;
        $this->active = $active;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    public function getLanguagePrivilegesCollection(): array
    {
        throw new \BadMethodCallException('Not implemented method.');
    }
}
