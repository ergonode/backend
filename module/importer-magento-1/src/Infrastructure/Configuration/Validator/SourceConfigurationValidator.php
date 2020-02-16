<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Configuration\Validator;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

/**
 */
class SourceConfigurationValidator
{
    /**
     * @param array $data
     *
     * @return Constraint
     */
    public function build(array $data): Constraint
    {
        return new Collection(
            [
                'stores' => new All(
                    [
                        'constrains' => [
                            new Collection(
                                [
                                    'store' => [
                                        new NotBlank(),
                                        new Length(['max' => 255]),
                                    ],
                                    'language' => [
                                        new NotBlank(),
                                    ],
                                ]
                            ),
                        ],
                    ]
                ),
            ]
        );
    }
}