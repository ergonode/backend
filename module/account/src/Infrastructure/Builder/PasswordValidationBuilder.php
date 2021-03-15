<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Builder;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @todo Watch at Collection issue (https://github.com/symfony/symfony/issues/30302)
 */
class PasswordValidationBuilder
{
    /**
     * @param array $data
     */
    public function create(array $data): Constraint
    {
        return new Collection([
            'password' => [
                new NotBlank(),
                new Length(['min' => 6, 'max' => 32]),
            ],
            'password_repeat' => [
                new NotBlank(),
                new Callback(function ($value, ExecutionContextInterface $context, $payload) use ($data): void {
                    if ($data['password'] !== $value) {
                        $context->addViolation('Password repeat must be identical');
                    }
                }),
            ],
        ]);
    }
}
