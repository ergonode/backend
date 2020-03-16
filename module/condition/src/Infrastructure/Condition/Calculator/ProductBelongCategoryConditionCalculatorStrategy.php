<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Condition\Domain\Condition\ProductBelongCategoryCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Webmozart\Assert\Assert;

/**
 */
class ProductBelongCategoryConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $repository;

    /**
     * ProductBelongCategoryConditionCalculatorStrategy constructor.
     * @param CategoryRepositoryInterface $repository
     */
    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

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
        $categoryId = $configuration->getCategory();

        $category = $this->repository->load($categoryId);
        Assert::notNull($category);

        $belong = $configuration->getOperator() === ProductBelongCategoryCondition::BELONG_TO;

        return $object->belongToCategory($category->getId()) === $belong;
    }
}
