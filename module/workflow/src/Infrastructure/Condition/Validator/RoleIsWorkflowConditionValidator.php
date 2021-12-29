<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Condition\Validator;

use Ergonode\Workflow\Domain\Condition\WorkflowConditionValidatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Ergonode\Workflow\Infrastructure\Condition\RoleIsWorkflowCondition;
use Ergonode\Account\Application\Validator\RoleExists;

class RoleIsWorkflowConditionValidator implements WorkflowConditionValidatorInterface
{
    public function supports(string $type): bool
    {
        return $type === RoleIsWorkflowCondition::TYPE;
    }

    public function build(): Constraint
    {
        return new Collection([
            'role' => [
                new NotBlank(),
                new RoleExists(),
            ],
        ]);
    }
}
