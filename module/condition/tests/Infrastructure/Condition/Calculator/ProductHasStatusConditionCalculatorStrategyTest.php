<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Tests\Infrastructure\Condition\Calculator;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Condition\Domain\Condition\ProductHasStatusCondition;
use Ergonode\Condition\Infrastructure\Condition\Calculator\ProductHasStatusConditionCalculatorStrategy;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class ProductHasStatusConditionCalculatorStrategyTest extends TestCase
{
    /**
     * @var StatusQueryInterface|MockObject
     */
    private MockObject $statusQuery;

    /**
     * @var ProductHasStatusConditionCalculatorStrategy
     */
    private ProductHasStatusConditionCalculatorStrategy $strategy;

    /**
     */
    protected function setUp(): void
    {

        $this->strategy =
            new ProductHasStatusConditionCalculatorStrategy();
    }


    /**
     */
    public function testSupports(): void
    {
        $this->assertTrue($this->strategy->supports('PRODUCT_HAS_STATUS_CONDITION'));
        $this->assertFalse($this->strategy->supports('PRODUCT'));
    }

    /**
     * @param string $operator
     * @param string $productStatusUuid
     * @param array  $searchedStatusIds
     * @param bool   $expectedResult
     *
     * @throws \Exception
     *
     * @dataProvider calculateProvider
     */
    public function testCalculate(
        string $operator,
        string $productStatusUuid,
        array $searchedStatusIds,
        bool $expectedResult
    ): void {
        $product = $this->createProductMock('some-id');
        $statusAttributeCode = new AttributeCode(StatusSystemAttribute::CODE);

        $product
            ->method('hasAttribute')
            ->willReturn(true);

        $product
            ->method('getAttribute')
            ->willReturn(new StringValue($productStatusUuid));

        $condition = $this->createProductHasStatusConditionMock($operator, $searchedStatusIds);
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
                'code1',
                ['code1', 'code2'],
                true,
            ],
            'HAS false' => [
                'HAS',
                'code1',
                ['code2', 'code3'],
                false,
            ],
            'NOT_HAS false' => [
                'NOT_HAS',
                'code1',
                ['code1', 'code2'],
                false,
            ],
            'NOT_HAS true' => [
                'NOT_HAS',
                'code1',
                ['code2', 'code3'],
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
     * @param array  $searchedStatuses
     *
     * @return MockObject
     */
    private function createProductHasStatusConditionMock(string $operator, array $searchedStatuses)
    {
        $mock = $this->createMock(ProductHasStatusCondition::class);
        $mock->method('getOperator')->willReturn($operator);
        foreach ($searchedStatuses as $searchedStatus) {
            $searchedStatusIds[] = StatusId::fromCode($searchedStatus);
        }
        $mock->method('getValue')->willReturn($searchedStatusIds);

        return $mock;
    }
}
