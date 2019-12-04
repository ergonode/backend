<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Condition\Validator;

use Ergonode\Account\Infrastructure\Validator\RoleExists;
use Ergonode\Condition\Domain\Condition\RoleExactlyCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionValidatorStrategyInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 */
class RoleExactlyConditionValidatorStrategy implements ConditionValidatorStrategyInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supports(string $type): bool
    {
        return $type === RoleExactlyCondition::TYPE;
    }

    /**
     * @param array $data
     *
     * @return Constraint
     */
    public function build(array $data): Constraint
    {
        return new Collection([
            'role' => [
                new NotBlank(),
                new RoleExists(),
            ],
        ]);
    }
}
