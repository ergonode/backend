<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Condition\Domain\Condition\ProductHasTemplateCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;

class ProductHasTemplateConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return ProductHasTemplateCondition::TYPE === $type;
    }

    /**
     * @param ConditionInterface|ProductHasTemplateCondition $configuration
     */
    public function calculate(AbstractProduct $product, ConditionInterface $configuration): bool
    {
        if (!$configuration instanceof ProductHasTemplateCondition) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    ProductHasTemplateCondition::class,
                    get_debug_type($configuration)
                )
            );
        }
        $productTemplateId = $product->getTemplateId();
        $searchedTemplateId = $configuration->getTemplateId();

        switch ($configuration->getOperator()) {
            case ProductHasTemplateCondition::HAS:
                return $productTemplateId->isEqual($searchedTemplateId);
            case ProductHasTemplateCondition::NOT_HAS:
                return !$productTemplateId->isEqual($searchedTemplateId);
        }

        throw new \RuntimeException(sprintf('Operator %s is not supported', $configuration->getOperator()));
    }
}
