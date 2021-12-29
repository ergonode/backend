<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Form\Model;

use Ergonode\Account\Application\Validator as AccountAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserApplyTokenModel
{
    /**
     * @Assert\NotBlank()
     * @AccountAssert\TokenAvailable()
     */
    public ?string $token = null;

    /**
     * @Assert\NotBlank()
     * @Assert\EqualTo(propertyPath="password", message="This value should be the same as password")
     */
    public ?string $passwordRepeat = null;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min="6",
     *     max="32",
     *     minMessage="User password is too short. It should have at least {{ limit }} characters.",
     *     maxMessage="User password is too long. It should contain {{ limit }} characters or less."
     * )
     */
    public ?string $password = null;

    /**
     * @Assert\Callback()
     *
     * @param mixed $payload
     */
    public function validatePassword(ExecutionContextInterface $context, $payload): void
    {
        /** @var UserApplyTokenModel $data */
        $data = $context->getValue();

        if ((string) $data->password !== (string) $data->passwordRepeat) {
            $context->addViolation('Password and password repeat must be identical');
        }
    }
}
