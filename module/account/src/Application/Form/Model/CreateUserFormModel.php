<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Form\Model;

use Ergonode\Account\Application\Validator\Constraints\UserUnique;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class CreateUserFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="User first name is required")
     * @Assert\Length(min="1", max="128")
     */
    public ?string $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="User last name is required")
     * @Assert\Length(min="3", max="128")
     */
    public ?string $lastName;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="User email is required")
     * @Assert\Email(mode="strict")
     *
     * @UserUnique()
     */
    public ?string $email;

    /**
     * @var Language
     *
     * @Assert\NotBlank(message="User language is required")
     */
    public ?Language $language;

    /**
     * @var Password|null
     *
     * @Assert\NotBlank(message="User password is required")
     * @Assert\Length(
     *     min="6",
     *     max="32",
     *     minMessage="User password is too short, should have at least {{ limit }} characters",
     *     maxMessage="User password is too long, should have at most {{ limit }} characters"
     * )
     */
    public ?Password $password;

    /**
     * @var Password|null
     *
     * @Assert\NotBlank(message="User password repeat is required")
     * @Assert\EqualTo(propertyPath="password", message="This value should be same as password")
     */
    public ?Password $passwordRepeat;

    /**
     * @var RoleId
     *
     * @Assert\NotBlank(message="Role Id is required")
     * @Assert\Uuid(message="Role Id must be valid uuid format")
     */
    public ?RoleId $roleId;

    /**
     * @var bool
     *
     * @Assert\NotNull(message="Activity is required")
     * @Assert\Type("boolean")
     */
    public ?bool $isActive;

    /**
     */
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
