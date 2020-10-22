<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Condition\Domain\Condition\ProductHasTemplateCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;

class ProductHasTemplateConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    private TemplateQueryInterface $templateQuery;

    public function __construct(TemplateQueryInterface $templateQuery)
    {
        $this->templateQuery = $templateQuery;
    }

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
        $productTemplateId = $this->templateQuery->findProductTemplateId($product->getId());
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
