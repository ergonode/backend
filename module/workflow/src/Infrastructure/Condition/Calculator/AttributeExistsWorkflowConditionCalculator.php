<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Condition\Calculator;

use Ergonode\Workflow\Domain\Condition\WorkflowConditionCalculatorInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Domain\Condition\WorkflowConditionInterface;
use Ergonode\Workflow\Infrastructure\Condition\AttributeExistsWorkflowCondition;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Workflow\Infrastructure\Exception\WorkflowConditionCalculatorException;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;

class AttributeExistsWorkflowConditionCalculator implements WorkflowConditionCalculatorInterface
{
    private AttributeRepositoryInterface $repository;

    public function __construct(AttributeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function supports(WorkflowConditionInterface $condition): bool
    {
        return $condition instanceof AttributeExistsWorkflowCondition;
    }

    /**
     * @param AttributeExistsWorkflowCondition $condition
     */
    public function calculate(AbstractProduct $product, WorkflowConditionInterface $condition, Language $language): bool
    {
        if (!$this->supports($condition)) {
            throw new WorkflowConditionCalculatorException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    AttributeExistsWorkflowCondition::class,
                    get_debug_type($condition)
                )
            );
        }

        $attributeId = $condition->getAttribute();

        $attribute = $this->repository->load($attributeId);

        Assert::isInstanceOf($attribute, AbstractAttribute::class);

        return $product->hasAttribute($attribute->getCode());
    }
}
