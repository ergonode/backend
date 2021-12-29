<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Condition\Calculator;

use Ergonode\Workflow\Domain\Condition\WorkflowConditionCalculatorInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Domain\Condition\WorkflowConditionInterface;
use Ergonode\Workflow\Infrastructure\Exception\WorkflowConditionCalculatorException;
use Ergonode\Workflow\Infrastructure\Condition\ProductCompletenessWorkflowCondition;
use Ergonode\Condition\Domain\Condition\ProductCompletenessCondition;
use Ergonode\Completeness\Infrastructure\Persistence\Query\CompletenessQuery;

class ProductCompletenessWorkflowConditionCalculator implements WorkflowConditionCalculatorInterface
{
    private CompletenessQuery $query;

    public function __construct(CompletenessQuery $query)
    {
        $this->query = $query;
    }

    public function supports(WorkflowConditionInterface $condition): bool
    {
        return $condition instanceof ProductCompletenessWorkflowCondition;
    }

    /**
     * @param ProductCompletenessWorkflowCondition $condition
     */
    public function calculate(AbstractProduct $product, WorkflowConditionInterface $condition, Language $language): bool
    {
        if (!$this->supports($condition)) {
            throw new WorkflowConditionCalculatorException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    ProductCompletenessWorkflowCondition::class,
                    get_debug_type($condition)
                )
            );
        }

        $result = true;

        $calculation = $this->query->hasCompleteness($product->getId(), $language);
        if ($condition->getCompleteness() === ProductCompletenessCondition::COMPLETE) {
            if (!$calculation) {
                $result = false;
            }
        } elseif ($calculation) {
            $result = false;
        }

        return $result;
    }
}
