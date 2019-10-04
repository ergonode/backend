<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Builder;

use Ergonode\Condition\Infrastructure\Validator\UniqueConditionSetCode;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Optional;

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
                    new UniqueConditionSetCode(),
                ],
                'name' => [
                    new Optional([
                        new All([
                            new Length(['min' => 2, 'max' => 255]),
                        ]),
                    ]),
                ],
                'description' => [
                    new Optional([
                        new All([
                            new Length(['max' => 255]),
                        ]),
                    ]),
                ],
            ],
        ]);
    }
}
