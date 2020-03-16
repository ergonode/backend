<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Tests\Infrastructure\Condition\Calculator;

use Ergonode\Category\Domain\Entity\CategoryTree;
use Ergonode\Category\Domain\Repository\TreeRepositoryInterface;
use Ergonode\Condition\Domain\Condition\ProductBelongCategoryTreeCondition;
use Ergonode\Condition\Infrastructure\Condition\Calculator\ProductBelongCategoryTreeConditionCalculatorStrategy;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductBelongCategoryTreeConditionCalculatorStrategyTest extends TestCase
{
    /**
     * @var MockObject|TreeRepositoryInterface
     */
    private MockObject $repository;

    /**
     * @var ProductBelongCategoryTreeConditionCalculatorStrategy
     */
    private ProductBelongCategoryTreeConditionCalculatorStrategy $strategy;

    /**
     */
    protected function setUp(): void
    {
        $this->repository = $this->createMock(TreeRepositoryInterface::class);
        $this->strategy = new ProductBelongCategoryTreeConditionCalculatorStrategy($this->repository);
    }

    /**
     */
    public function testSupports(): void
    {
        $this->assertTrue($this->strategy->supports('PRODUCT_BELONG_CATEGORY_TREE_CONDITION'));
        $this->assertFalse($this->strategy->supports('PRODUCT'));
    }
    /**
     * @param string $operator
     * @param bool   $result
     *
     * @dataProvider dataProvider
     */
    public function testCalculate(string $operator, bool $result): void
    {
        $object = $this->createMock(AbstractProduct::class);
        $configuration = $this->createMock(ProductBelongCategoryTreeCondition::class);
        $configuration
            ->expects($this->once())
            ->method('getTree')
            ->willReturn($this->createMock(CategoryTreeId::class));

        $this
            ->repository
            ->expects($this->once())
            ->method('load')
            ->willReturn($this->createMock(CategoryTree::class));

        $configuration->expects($this->once())->method('getOperator')->willReturn($operator);
        $this->assertSame($result, $this->strategy->calculate($object, $configuration));
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'operator' => 'BELONG_TO',
                'result' => false,
            ],
            [
                'operator' => 'NOT_BELONG_TO',
                'result' => true,
            ],
        ];
    }
}
