<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Infrastructure\Condition\Calculator;

use Ergonode\Condition\Domain\Condition\ProductHasTemplateCondition;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\TestCase;
use Ergonode\Condition\Infrastructure\Condition\Calculator\ProductHasTemplateConditionCalculatorStrategy;
use PHPUnit\Framework\MockObject\MockObject;

class ProductHasTemplateConditionCalculatorStrategyTest extends TestCase
{
    private ProductHasTemplateConditionCalculatorStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new ProductHasTemplateConditionCalculatorStrategy();
    }

    public function testSupports(): void
    {
        $this->assertTrue($this->strategy->supports('PRODUCT_HAS_TEMPLATE_CONDITION'));
        $this->assertFalse($this->strategy->supports('PRODUCT'));
    }

    /**
     * @throws \Exception
     *
     * @dataProvider calculateProvider
     */
    public function testCalculate(
        string $operator,
        TemplateId $productTemplateId,
        TemplateId $searchedTemplateId,
        bool $expectedResult
    ): void {
        $product = $this->createProductMock('some-id');
        $product->method('getTemplateId')->willReturn($productTemplateId);
        $condition = $this->createProductHasTemplateConditionMock($operator, $searchedTemplateId);

        $result = $this->strategy->calculate($product, $condition);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function calculateProvider(): array
    {
        return [
            'HAS true' => [
                'HAS',
                new TemplateId('0e03f56c-7b1f-4ff6-9603-01a968a4d12f'),
                new TemplateId('0e03f56c-7b1f-4ff6-9603-01a968a4d12f'),
                true,
            ],
            'HAS false' => [
                'HAS',
                new TemplateId('0e03f56c-7b1f-4ff6-9603-01a968a4d12f'),
                new TemplateId('35cb5027-544f-456b-8697-ce4b6932390b'),
                false,
            ],
            'NOT_HAS false' => [
                'NOT_HAS',
                new TemplateId('0e03f56c-7b1f-4ff6-9603-01a968a4d12f'),
                new TemplateId('0e03f56c-7b1f-4ff6-9603-01a968a4d12f'),
                false,
            ],
            'NOT_HAS true' => [
                'NOT_HAS',
                new TemplateId('0e03f56c-7b1f-4ff6-9603-01a968a4d12f'),
                new TemplateId('35cb5027-544f-456b-8697-ce4b6932390b'),
                true,
            ],
        ];
    }

    /**
     * @return AbstractProduct|MockObject
     */
    private function createProductMock(string $productId)
    {
        $productIdMock = $this
            ->createMock(ProductId::class);
        $productIdMock->method('getValue')->willReturn($productId);

        $productMock = $this->createMock(AbstractProduct::class);
        $productMock->method('getId')->willReturn($productIdMock);

        return $productMock;
    }

    /**
     * @return ProductHasTemplateCondition|MockObject
     */
    private function createProductHasTemplateConditionMock(string $operator, TemplateId $searchedTemplateId)
    {
        $mock = $this->createMock(ProductHasTemplateCondition::class);
        $mock->method('getOperator')->willReturn($operator);
        $mock->method('getTemplateId')->willReturn($searchedTemplateId);

        return $mock;
    }
}
