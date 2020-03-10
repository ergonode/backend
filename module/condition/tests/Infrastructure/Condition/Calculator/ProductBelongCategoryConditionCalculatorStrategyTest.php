<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

namespace Ergonode\Condition\Tests\Infrastructure\Condition\Calculator;

use Ergonode\Category\Domain\Entity\Category;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Condition\Domain\Condition\ProductBelongCategoryCondition;
use Ergonode\Condition\Infrastructure\Condition\Calculator\ProductBelongCategoryConditionCalculatorStrategy;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductBelongCategoryConditionCalculatorStrategyTest extends TestCase
{

    /**
     * @var MockObject|CategoryRepositoryInterface
     */
    private MockObject $repository;

    /**
     * @var ProductBelongCategoryConditionCalculatorStrategy
     */
    private ProductBelongCategoryConditionCalculatorStrategy $strategy;

    /**
     */
    protected function setUp(): void
    {
        $this->repository = $this->createMock(CategoryRepositoryInterface::class);
        $this->strategy = new ProductBelongCategoryConditionCalculatorStrategy($this->repository);
    }

    /**
     */
    public function testSupports(): void
    {
        $this->assertTrue($this->strategy->supports('PRODUCT_BELONG_CATEGORY_CONDITION'));
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
        $configuration = $this->createMock(ProductBelongCategoryCondition::class);
        $configuration
            ->expects($this->once())
            ->method('getCategory')
            ->willReturn($this->createMock(CategoryId::class));
        $this
            ->repository
            ->expects($this->once())
            ->method('load')
            ->willReturn($this->createMock(Category::class));

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
