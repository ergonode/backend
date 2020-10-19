<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Tests\Infrastructure\Condition\Calculator;

use Ergonode\Condition\Domain\Condition\ProductBelongCategoryCondition;
use Ergonode\Condition\Infrastructure\Condition\Calculator\ProductBelongCategoryConditionCalculatorStrategy;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductBelongCategoryConditionCalculatorStrategyTest extends TestCase
{
    /**
     * @var ProductBelongCategoryConditionCalculatorStrategy
     */
    private ProductBelongCategoryConditionCalculatorStrategy $strategy;

    /**
     */
    protected function setUp(): void
    {
        $this->strategy = new ProductBelongCategoryConditionCalculatorStrategy();
    }

    /**
     */
    public function testSupports(): void
    {
        self::assertTrue($this->strategy->supports('PRODUCT_BELONG_CATEGORY_CONDITION'));
        self::assertFalse($this->strategy->supports('PRODUCT'));
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
            ->expects(self::once())
            ->method('getCategory')
            ->willReturn(
                [
                    $this->createMock(CategoryId::class),
                ]
            );

        $configuration->expects(self::once())->method('getOperator')->willReturn($operator);
        self::assertSame($result, $this->strategy->calculate($object, $configuration));
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
