<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Command;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;

/**
 */
class CreateUserCommand
{
    /**
     * @var UserId
     */
    private $id;

    /**
     * @var MultimediaId|null
     */
    private $avatarId;

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
     * @var Password
     */
    private $password;

    /**
     * @var Language
     */
    private $language;

    /**
     * @param string            $firstName
     * @param string            $lastName
     * @param string            $email
     * @param Language          $language
     * @param Password          $password
     * @param MultimediaId|null $avatarId
     *
     * @throws \Exception
     */
    public function __construct(string $firstName, string $lastName, string $email, Language $language, Password $password, ?MultimediaId $avatarId = null)
    {
        $this->id = UserId::generate();
        $this->avatarId = $avatarId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->language = $language;
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
     * @return string
     */
    public function getEmail(): string
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
}
