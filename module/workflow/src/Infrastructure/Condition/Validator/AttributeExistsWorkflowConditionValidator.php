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
use Ergonode\Attribute\Application\Validator\AttributeExists;
use Ergonode\Workflow\Infrastructure\Condition\AttributeExistsWorkflowCondition;

class AttributeExistsWorkflowConditionValidator implements WorkflowConditionValidatorInterface
{
    public function supports(string $type): bool
    {
        return $type === AttributeExistsWorkflowCondition::TYPE;
    }

    public function build(): Constraint
    {
        return new Collection(
            [
                'attribute' => [
                    new NotBlank(),
                    new AttributeExists(),
                ],
            ]
        );
    }
}
