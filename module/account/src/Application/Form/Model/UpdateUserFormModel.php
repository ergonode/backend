<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Form\Model;

use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 */
class UpdateUserFormModel
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
     * @var Language
     *
     * @Assert\NotBlank(message="User language is required")
     */
    public ?Language $language;

    /**
     * @var Password|null
     */
    public ?Password $passwordRepeat;

    /**
     * @var Password|null
     *
     * @Assert\Length(
     *     min="6",
     *     max="32",
     *     minMessage="User password is too short, should have at least {{ limit }} characters",
     *     maxMessage="User password is too long, should have at most {{ limit }} characters"
     * )
     */
    public ?Password $password;

    /**
     * @var bool
     *
     * @Assert\NotNull(message="Activity is required")
     * @Assert\Type("bool")
     */
    public ?bool $isActive;

    /**
     * @var RoleId
     *
     * @Assert\NotBlank(message="Role Id is required")
     * @Assert\Uuid(message="Role Id must be valid uuid format")
     */
    public ?RoleId $roleId;

    /**
     */
    public function __construct()
    {
        $this->firstName = null;
        $this->lastName = null;
        $this->language = null;
        $this->passwordRepeat = null;
        $this->password = null;
        $this->isActive = null;
        $this->roleId = null;
    }

    /**
     * @Assert\Callback()
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    public function validatePassword(ExecutionContextInterface $context, $payload)
    {
        /** @var UpdateUserFormModel $data */
        $data = $context->getValue();

        if ((string) $data->password !== (string) $data->passwordRepeat) {
            $context->addViolation('Password and password repeat must be identical');
        }
    }
}
