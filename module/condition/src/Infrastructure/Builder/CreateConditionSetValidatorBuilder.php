<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Builder;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 */
class CreateConditionSetValidatorBuilder
{
    /**
     * @param array $data
     *
     * @return Constraint
     */
    public function build(array $data): Constraint
    {
        return new Collection([
            'fields' => [
                'code' => [
                    new NotBlank(),
                    new Length(['min' => 2, 'max' => 100]),
                ],
                'name' => [
                    new NotBlank(),
                    new All([
                        new Length(['min' => 2, 'max' => 255]),
                    ]),
                ],
                'description' => [
                    new NotBlank(),
                    new All([
                        new Length(['max' => 255]),
                    ]),
                ],
            ],
        ]);
    }
}
