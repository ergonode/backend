<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Condition\Domain\Condition\ProductSkuExistsCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;

class ProductSkuExistsConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return ProductSkuExistsCondition::TYPE === $type;
    }

    /**
     * @inheritDoc
     */
    public function calculate(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        if (!$configuration instanceof ProductSkuExistsCondition) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    ProductSkuExistsCondition::class,
                    get_debug_type($configuration)
                )
            );
        }
        $operator = $configuration->getOperator();
        $pattern = $value = strtolower($configuration->getValue());
        $sku = strtolower($object->getSku()->getValue());

        switch ($operator) {
            case ProductSkuExistsCondition::IS_EQUAL:
                return $value === $sku;
            case ProductSkuExistsCondition::IS_NOT_EQUAL:
                return $value !== $sku;
            case ProductSkuExistsCondition::HAS:
                return strpos($sku, $pattern) !== false;
            case ProductSkuExistsCondition::WILDCARD:
                return fnmatch($pattern, $sku) !== false;
            case ProductSkuExistsCondition::REGEXP:
                return preg_match($configuration->getValue(), $object->getSku()->getValue()) >= 1;
        }

        throw new \RuntimeException(sprintf('Operator %s is not supported', $configuration->getOperator()));
    }
}
