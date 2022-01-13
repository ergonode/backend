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
use Ergonode\Workflow\Infrastructure\Condition\AttributeExistsWorkflowCondition;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Workflow\Infrastructure\Exception\WorkflowConditionCalculatorException;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

class AttributeExistsWorkflowConditionCalculator implements WorkflowConditionCalculatorInterface
{
    private AttributeRepositoryInterface $repository;

    private LanguageQueryInterface $languageQuery;

    public function __construct(
        AttributeRepositoryInterface $repository,
        LanguageQueryInterface $languageQuery
    ) {
        $this->repository = $repository;
        $this->languageQuery = $languageQuery;
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

        if ($product->hasAttribute($attribute->getCode())) {
            $value = $product->getAttribute($attribute->getCode());
            if ($value->hasTranslation($language)) {
                return true;
            }

            if ($this->hasAncestorValue($value, $language)) {
                return true;
            }
        }

        return false;
    }

    private function hasAncestorValue(ValueInterface $value, Language $language): bool
    {
        $ancestorLanguages = $this->languageQuery->getInheritancePath($language);
        foreach ($ancestorLanguages as $ancestorLanguage) {
            if ($value->hasTranslation($ancestorLanguage)) {
                return true;
            }
        }

        return false;
    }
}
