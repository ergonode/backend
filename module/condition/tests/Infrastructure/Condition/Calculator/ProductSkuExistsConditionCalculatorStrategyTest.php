<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Infrastructure\Condition\Calculator;

use Ergonode\Condition\Domain\Condition\ProductSkuExistsCondition;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\ValueObject\Sku;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Ergonode\Condition\Infrastructure\Condition\Calculator\ProductSkuExistsConditionCalculatorStrategy;

class ProductSkuExistsConditionCalculatorStrategyTest extends TestCase
{
    private ProductSkuExistsConditionCalculatorStrategy $strategy;

    public function testSupports(): void
    {
        $this->assertTrue($this->strategy->supports('PRODUCT_SKU_EXISTS_CONDITION'));
        $this->assertFalse($this->strategy->supports('test'));
    }


    /**
     * @dataProvider calculateProvider
     */
    public function testCalculate(string $sku, string $operator, string $value, bool $result): void
    {
        $product = $this->createProductMock($sku);
        $condition = $this->createProductSkuExistsConditionMock($operator, $value);

        $this->assertSame(
            $result,
            $this->strategy->calculate($product, $condition)
        );
    }

    /**
     * @return array
     */
    public function calculateProvider(): array
    {
        return [
            'IS_EQUAL true' =>
                [
                    'SKU_123',
                    ProductSkuExistsCondition::IS_EQUAL,
                    'SKU_123',
                    true,
                ],
            'IS_EQUAL false' =>
                [
                    'SKU_123',
                    ProductSkuExistsCondition::IS_EQUAL,
                    'SKU_1213',
                    false,
                ],
            'IS_NOT_EQUAL true' =>
                [
                    'SKU_123',
                    ProductSkuExistsCondition::IS_NOT_EQUAL,
                    'SKU_123123',
                    true,
                ],
            'IS_NOT_EQUAL false' =>
                [
                    'SKU_123123',
                    ProductSkuExistsCondition::IS_NOT_EQUAL,
                    'SKU_123123',
                    false,
                ],
            'HAS true 1' =>
                [
                    'SKU_123123',
                    ProductSkuExistsCondition::HAS,
                    '123',
                    true,
                ],
            'HAS true 2' =>
                [
                    'SKU_123123',
                    ProductSkuExistsCondition::HAS,
                    'SKU',
                    true,
                ],
            'HAS true 3' =>
                [
                    'SKU_1231239',
                    ProductSkuExistsCondition::HAS,
                    '9',
                    true,
                ],
            'WILDCARD true 1' =>
                [
                    'SKU_1231239',
                    ProductSkuExistsCondition::WILDCARD,
                    'SKU*',
                    true,
                ],
            'WILDCARD true 2' =>
                [
                    'SKU_123',
                    ProductSkuExistsCondition::WILDCARD,
                    'SKU_?[23]3',
                    true,
                ],
            'WILDCARD true 3' =>
                [
                    'SKU_133',
                    ProductSkuExistsCondition::WILDCARD,
                    'SKU_?[23]3',
                    true,
                ],
            'WILDCARD false 1' =>
                [
                    'PKU_133',
                    ProductSkuExistsCondition::WILDCARD,
                    'SKU_*',
                    false,
                ],
            'WILDCARD false 2' =>
                [
                    'SKU_143',
                    ProductSkuExistsCondition::WILDCARD,
                    'SKU_?[23]3',
                    false,
                ],
            'WILDCARD false 3' =>
                [
                    'SKU_1',
                    ProductSkuExistsCondition::WILDCARD,
                    'SKU_?[23]3',
                    false,
                ],
            'REGEXP true 1' =>
                [
                    'SKU_123',
                    ProductSkuExistsCondition::WILDCARD,
                    'SKU_123',
                    true,
                ],
            'REGEXP true 2' =>
                [
                    'SKU_123',
                    ProductSkuExistsCondition::REGEXP,
                    '/SKU_[0-9]{3}/',
                    true,
                ],
            'REGEXP false 1' =>
                [
                    'SKU_123',
                    ProductSkuExistsCondition::REGEXP,
                    '/sku_123/',
                    false,
                ],
            'REGEXP false 2' =>
                [
                    'SKU_1234',
                    ProductSkuExistsCondition::REGEXP,
                    '/^SKU_[0-9]{3}$/',
                    false,
                ],
        ];
    }

    protected function setUp(): void
    {
        $this->strategy = new ProductSkuExistsConditionCalculatorStrategy();
    }


    /**
     * @return AbstractProduct|MockObject
     */
    private function createProductMock(string $sku)
    {
        $skuMock = $this
            ->createMock(Sku::class);
        $skuMock->method('getValue')->willReturn($sku);

        $productMock = $this->createMock(AbstractProduct::class);
        $productMock->method('getSku')->willReturn($skuMock);

        return $productMock;
    }

    /**
     * @return ProductSkuExistsCondition|MockObject
     */
    private function createProductSkuExistsConditionMock(string $operator, string $value)
    {
        $conditionMock = $this->createMock(ProductSkuExistsCondition::class);
        $conditionMock
            ->method('getOperator')->willReturn($operator);
        $conditionMock->method('getValue')->willReturn($value);

        return $conditionMock;
    }
}
