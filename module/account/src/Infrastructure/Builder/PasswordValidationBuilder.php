<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Builder;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\IdenticalTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 */
class PasswordValidationBuilder
{
    /**
     * @return Constraint
     */
    public function create(): Constraint
    {
        return new Collection(
            [
                'password' => [
                    new NotBlank(),
                    new Length(['min' => 6, 'max' => 32]),
                ],
                'password_repeat' => [
                    new IdenticalTo(['propertyPath' => 'password']),
                ],
            ]
        );
    }
}
