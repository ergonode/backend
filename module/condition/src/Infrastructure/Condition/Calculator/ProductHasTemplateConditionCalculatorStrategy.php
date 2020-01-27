<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Condition\Domain\Condition\ProductHasTemplateCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Designer\Domain\Repository\TemplateRepositoryInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
class ProductHasTemplateConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    /**
     * @var TemplateQueryInterface
     */
    private TemplateQueryInterface $templateQuery;

    /**
     * @param TemplateQueryInterface $templateQuery
     */
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
     * @inheritDoc
     */
    public function calculate(AbstractProduct $product, ConditionInterface $configuration): bool
    {
        $productTemplateId = $this->templateQuery->findProductTemplateId($product->getId());
        $searchedTemplateId = TemplateId::fromKey($configuration->getValue());

        switch ($configuration->getOperator()) {
            case ProductHasTemplateCondition::HAS:
                return  $productTemplateId->isEqual($searchedTemplateId);
            case ProductHasTemplateCondition::NOT_HAS:
                return  !$productTemplateId->isEqual($searchedTemplateId);
        }
    }
}
