<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Authentication\Application\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 */
class User implements UserInterface
{
    private string $id;
    private string $password;
    private array $roles;

    /**
     * @param string $id
     * @param string $password
     * @param array  $roles
     */
    public function __construct(string $id, string $password, array $roles)
    {
        $this->id = $id;
        $this->password = $password;
        $this->roles = $roles;
    }

    /**
     * @return string
     */
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
    public function eraseCredentials()
    {
    }
}
