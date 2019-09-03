<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Builder;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

/**
 */
class TransitionValidatorBuilder
{
    /**
     * @param array    $data
     * @param string[] $statuses
     *
     * @return Constraint
     */
    public function build(array $data, array $statuses = []): Constraint
    {
        return new Collection(
            [
                'name' => [
                    new NotBlank(),
                    new Length(['max' => 32]),
                ],
                'source' => [
                    new NotBlank(),
                    new Uuid(),
                    new Choice(['choices' => $statuses, 'message' => 'Source status not exist in workflow']),
                ],
                'destination' => [
                    new NotBlank(),
                    new Uuid(),
                    new Choice(['choices' => $statuses, 'message' => 'Destination status not exist in workflow']),
                ],
            ]
        );
    }
}
