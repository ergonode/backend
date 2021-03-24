<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Infrastructure\Condition\Calculator;

use Ergonode\Condition\Domain\Condition\ProductBelongCategoryCondition;
use Ergonode\Condition\Infrastructure\Condition\Calculator\ProductBelongCategoryConditionCalculatorStrategy;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use PHPUnit\Framework\TestCase;

class ProductBelongCategoryConditionCalculatorStrategyTest extends TestCase
{
    private ProductBelongCategoryConditionCalculatorStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new ProductBelongCategoryConditionCalculatorStrategy();
    }

    public function testSupports(): void
    {
        $this->assertTrue($this->strategy->supports('PRODUCT_BELONG_CATEGORY_CONDITION'));
        $this->assertFalse($this->strategy->supports('PRODUCT'));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testCalculate(string $operator, bool $result): void
    {
        $object = $this->createMock(AbstractProduct::class);
        $configuration = $this->createMock(ProductBelongCategoryCondition::class);
        $configuration
            ->expects($this->once())
            ->method('getCategory')
            ->willReturn(
                [
                    $this->createMock(CategoryId::class),
                ]
            );

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
