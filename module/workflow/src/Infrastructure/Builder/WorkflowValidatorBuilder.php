<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Builder;

use Ergonode\Workflow\Infrastructure\Validator\StatusNotExists;
use Ergonode\Workflow\Infrastructure\Validator\WorkflowExists;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 */
class WorkflowValidatorBuilder
{
    public const UNIQUE_WORKFLOW = 'WorkflowExists';

    /**
     * @param array $data
     *
     * @return Constraint
     */
    public function build(array $data): Constraint
    {
        return new Collection(
            [
                'code' => [
                    new NotBlank(),
                    new Length(['max' => 100]),
                    new WorkflowExists(['groups' => self::UNIQUE_WORKFLOW]),
                ],
                'statuses' => new All(
                    [
                        'constraints' => [
                            new NotBlank(),
                            new StatusNotExists(),
                        ],
                    ]
                ),
            ]
        );
    }
}
