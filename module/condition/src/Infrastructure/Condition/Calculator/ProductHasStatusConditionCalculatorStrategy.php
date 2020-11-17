<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Condition\Domain\Condition\ProductHasStatusCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Webmozart\Assert\Assert;

class ProductHasStatusConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return ProductHasStatusCondition::TYPE === $type;
    }

    /**
     * @inheritDoc
     */
    public function calculate(AbstractProduct $product, ConditionInterface $configuration): bool
    {
        if (!$configuration instanceof ProductHasStatusCondition) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    ProductHasStatusCondition::class,
                    get_debug_type($configuration)
                )
            );
        }
        $statusAttributeCode = new AttributeCode(StatusSystemAttribute::CODE);

        Assert::true($product->hasAttribute($statusAttributeCode));
        $productStatuses = $product->getAttribute($statusAttributeCode)->getValue();
        $filteredProductStatuses = $this->getStatusIdsByLanguages($configuration->getLanguage(), $productStatuses);

        $result = [];
        foreach ($filteredProductStatuses as $filteredProductStatus) {
            foreach ($configuration->getValue() as $searchedStatusId) {
                $result[] = $filteredProductStatus->isEqual($searchedStatusId);
            }
        }
        switch ($configuration->getOperator()) {
            case ProductHasStatusCondition::HAS:
                if (in_array(true, $result, true)) {
                    return true;
                }
                break;
            case ProductHasStatusCondition::NOT_HAS:
                if (!in_array(true, $result, true)) {
                    return true;
                }
                break;
        }

        return false;
    }

    /**
     * @param Language[] $languages
     */
    private function getStatusIdsByLanguages(array $languages, array $statuses): array
    {
        $filteredProductStatuses = [];
        foreach ($languages as $language) {
            if (array_key_exists($language->getCode(), $statuses)) {
                $filteredProductStatuses[] = new StatusId($statuses[$language->getCode()]);
            }
        }

        return $filteredProductStatuses;
    }
}
