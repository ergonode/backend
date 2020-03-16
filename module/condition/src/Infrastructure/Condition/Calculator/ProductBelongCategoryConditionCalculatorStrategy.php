<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Condition\Domain\Condition\ProductBelongCategoryCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
class ProductBelongCategoryConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return ProductBelongCategoryCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function calculate(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        $belong = $configuration->getOperator() === ProductBelongCategoryCondition::BELONG_TO;

        if ($belong) {
            foreach ($configuration->getCategory() as $categoryId) {
                if ($object->belongToCategory($categoryId)) {
                    return true;
                }
            }

            return false;
        }

        //not belong
        foreach ($configuration->getCategory() as $categoryId) {
            if ($object->belongToCategory($categoryId)) {
                return false;
            }
        }

        return true;
    }
}
