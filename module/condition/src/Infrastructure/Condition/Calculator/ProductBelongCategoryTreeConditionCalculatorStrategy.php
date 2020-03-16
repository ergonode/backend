<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Category\Domain\Repository\TreeRepositoryInterface;
use Ergonode\Condition\Domain\Condition\ProductBelongCategoryTreeCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Webmozart\Assert\Assert;

/**
 */
class ProductBelongCategoryTreeConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    /**
     * @var TreeRepositoryInterface
     */
    private TreeRepositoryInterface $repository;

    /**
     * @param TreeRepositoryInterface $repository
     */
    public function __construct(TreeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return ProductBelongCategoryTreeCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function calculate(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        $categoryTreeId = $configuration->getTree();
        $categoryTree = $this->repository->load($categoryTreeId);
        Assert::notNull($categoryTree);

        $belong = $configuration->getOperator() === ProductBelongCategoryTreeCondition::BELONG_TO;
        $isset = false;
        foreach ($object->getCategories() as $categoryId) {
            if ($categoryTree->hasCategory($categoryId)) {
                $isset = true;
            }
        }

        return $belong === $isset;
    }
}
