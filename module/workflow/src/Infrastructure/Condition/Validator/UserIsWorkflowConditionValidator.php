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
use Ergonode\Workflow\Infrastructure\Condition\UserIsWorkflowCondition;
use Ergonode\Account\Application\Validator\UserExists;

class UserIsWorkflowConditionValidator implements WorkflowConditionValidatorInterface
{
    public function supports(string $type): bool
    {
        return $type === UserIsWorkflowCondition::TYPE;
    }

    public function build(): Constraint
    {
        return new Collection([
            'user' => [
                new NotBlank(),
                new UserExists(),
            ],
        ]);
    }
}
