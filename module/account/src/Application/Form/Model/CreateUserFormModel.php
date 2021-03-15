<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Form\Model;

use Ergonode\Account\Application\Validator as AccountAssert;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserFormModel
{
    /**
     * @Assert\NotBlank(message="User first name is required")
     * @Assert\Length(
     *     min="1",
     *     max="128",
     *     minMessage="User first name is too short. It should have at least {{ limit }} characters.",
     *     maxMessage="User first name is too long. It should contain {{ limit }} characters or less."
     * )
     */
    public ?string $firstName;

    /**
     * @Assert\NotBlank(message="User last name is required")
     * @Assert\Length(
     *     min="3",
     *     max="128",
     *     minMessage="User last name is too short. It should have at least {{ limit }} characters.",
     *     maxMessage="User last name is too long. It should contain {{ limit }} characters or less."
     * )
     */
    public ?string $lastName;

    /**
     * @Assert\NotBlank(message="User email is required")
     * @Assert\Email(mode="strict")
     * @Assert\Length(
     *     min="5",
     *     max="255",
     *     minMessage="User email is too short. It should have at least {{ limit }} characters.",
     *     maxMessage="User email is too long. It should contain {{ limit }} characters or less."
     * )
     * @AccountAssert\UserUnique()
     */
    public ?string $email;

    /**
     * @Assert\NotBlank(message="User language is required")
     */
    public ?Language $language;

    /**
     * @Assert\NotBlank(message="User password is required")
     * @Assert\Length(
     *     min="6",
     *     max="32",
     *     minMessage="User password is too short. It should have at least {{ limit }} characters.",
     *     maxMessage="User password is too long. It should contain {{ limit }} characters or less."
     * )
     */
    public ?string $password;

    /**
     * @Assert\NotBlank(message="User password repeat is required")
     * @Assert\EqualTo(propertyPath="password", message="This value should be the same as password")
     */
    public ?string $passwordRepeat;

    /**
     * @Assert\NotBlank(message="Role Id is required")
     * @Assert\Uuid(message="Role Id must be valid uuid format")
     * @AccountAssert\RoleExists()
     */
    public ?string $roleId;

    /**
     * @Assert\NotNull(message="Activity is required")
     * @Assert\Type("boolean")
     */
    public ?bool $isActive;

    public function __construct()
    {
        $this->firstName = null;
        $this->lastName = null;
        $this->email = null;
        $this->language = null;
        $this->password = null;
        $this->passwordRepeat = null;
        $this->roleId = null;
        $this->isActive = false;
    }
}
