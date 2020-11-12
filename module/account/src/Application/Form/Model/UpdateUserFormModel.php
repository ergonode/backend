<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Form\Model;

use Ergonode\Account\Application\Validator\Constraints as AccountAssert;
use Ergonode\Account\Domain\ValueObject\LanguagePrivileges;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UpdateUserFormModel
{
    /**
     * @Assert\NotBlank(message="User first name is required")
     * @Assert\Length(min="1", max="128")
     */
    public ?string $firstName;

    /**
     * @Assert\NotBlank(message="User last name is required")
     * @Assert\Length(min="3", max="128")
     */
    public ?string $lastName;

    /**
     * @Assert\NotBlank(message="User language is required")
     */
    public ?Language $language;

    /**
     * @Assert\EqualTo(propertyPath="password", message="This value should be the same as password")
     */
    public ?string $passwordRepeat;

    /**
     * @Assert\Length(
     *     min="6",
     *     max="32",
     *     minMessage="User password is too short. It should have at least {{ limit }} characters.",
     *     maxMessage="User password is too long. It should contain {{ limit }} characters or less."
     * )
     */
    public ?string $password;

    /**
     * @Assert\NotNull(message="Activity is required")
     * @Assert\Type("bool")
     */
    public ?bool $isActive;

    /**
     * @Assert\NotBlank(message="Role Id is required")
     * @Assert\Uuid(message="Role Id must be valid uuid format")
     */
    public ?RoleId $roleId;

    /**
     * @var LanguagePrivileges[] | null
     *
     * @AccountAssert\ConstraintLanguagePrivilegesRelations()
     * @AccountAssert\ConstraintLanguageActive()
     * @AccountAssert\ConstraintLanguageCodeExists()
     */
    public ?array $languagePrivilegesCollection;

    public function __construct()
    {
        $this->firstName = null;
        $this->lastName = null;
        $this->language = null;
        $this->passwordRepeat = null;
        $this->password = null;
        $this->isActive = null;
        $this->languagePrivilegesCollection = [];
        $this->roleId = null;
    }

    /**
     * @Assert\Callback()
     *
     * @param mixed $payload
     */
    public function validatePassword(ExecutionContextInterface $context, $payload): void
    {
        /** @var UpdateUserFormModel $data */
        $data = $context->getValue();

        if ((string) $data->password !== (string) $data->passwordRepeat) {
            $context->addViolation('Password and password repeat must be identical');
        }
    }
}
