<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Condition\Validator;

use Ergonode\Workflow\Domain\Condition\WorkflowConditionValidatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Ergonode\Workflow\Infrastructure\Condition\ProductCompletenessWorkflowCondition;
use Symfony\Component\Validator\Constraints\Choice;

class ProductCompletenessWorkflowConditionValidator implements WorkflowConditionValidatorInterface
{
    public function supports(string $type): bool
    {
        return $type === ProductCompletenessWorkflowCondition::TYPE;
    }

    public function build(): Constraint
    {
        return new Collection([
            'completeness' => [
                new NotBlank(),
                new Choice(ProductCompletenessWorkflowCondition::OPTIONS),
            ],
        ]);
    }
}
