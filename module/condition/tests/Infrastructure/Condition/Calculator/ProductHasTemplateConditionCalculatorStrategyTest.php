<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 */
class ProductHasTemplateConditionCalculatorStrategyTest extends TestCase
{
    /**
     * @var TemplateQueryInterface|MockObject
     */
    private MockObject $templateQuery;

    /**
     * @var ProductHasTemplateConditionCalculatorStrategy
     */
    private ProductHasTemplateConditionCalculatorStrategy $strategy;

    /**
     */
    protected function setUp(): void
    {
        $this->templateQuery = $this->createMock(TemplateQueryInterface::class);

        $this->strategy =
            new ProductHasTemplateConditionCalculatorStrategy($this->templateQuery);
    }


    /**
     */
    public function testSupports(): void
    {
        $this->assertTrue($this->strategy->supports('PRODUCT_HAS_TEMPLATE_CONDITION'));
        $this->assertFalse($this->strategy->supports('PRODUCT'));
    }

    /**
     * @param string $operator
     * @param string $productTemplateName
     * @param string $searchedTemplateName
     * @param bool   $expectedResult
     *
     * @throws \Exception
     *
     * @dataProvider calculateProvider
     */
    public function testCalculate(
        string $operator,
        string $productTemplateName,
        string $searchedTemplateName,
        bool $expectedResult
    ): void {
        $product = $this->createProductMock('some-id');
        $productTemplateId = TemplateId::fromKey($productTemplateName);
        $condition = $this->createProductHasTemplateConditionMock($operator, $searchedTemplateName);

        $this
            ->templateQuery
            ->method('findProductTemplateId')
            ->withConsecutive([$product->getId()])
            ->willReturn($productTemplateId);

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
                'aaa',
                'aaa',
                true,
            ],
            'HAS false' => [
                'HAS',
                'aaa',
                'ccc',
                false,
            ],
            'NOT_HAS false' => [
                'NOT_HAS',
                'aaa',
                'aaa',
                false,
            ],
            'NOT_HAS true' => [
                'NOT_HAS',
                'aaa',
                'ccc',
                true,
            ],
        ];
    }

    /**
     * @param string $productId
     *
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
     * @param string $operator
     * @param string $searchedTemplateName
     *
     * @return ProductHasTemplateCondition|MockObject
     */
    private function createProductHasTemplateConditionMock(string $operator, string $searchedTemplateName)
    {
        $mock = $this->createMock(ProductHasTemplateCondition::class);
        $mock->method('getOperator')->willReturn($operator);
        $mock->method('getValue')->willReturn($searchedTemplateName);

        return $mock;
    }
}
