<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Infrastructure\Condition\Calculator;

use Ergonode\Condition\Domain\Condition\ProductHasStatusCondition;
use Ergonode\Condition\Infrastructure\Condition\Calculator\ProductHasStatusConditionCalculatorStrategy;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductHasStatusConditionCalculatorStrategyTest extends TestCase
{
    private ProductHasStatusConditionCalculatorStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new ProductHasStatusConditionCalculatorStrategy();
    }


    public function testSupports(): void
    {
        $this->assertTrue($this->strategy->supports('PRODUCT_HAS_STATUS_CONDITION'));
        $this->assertFalse($this->strategy->supports('PRODUCT'));
    }

    /**
     * @param array $searchedStatusIds
     *
     * @throws \Exception
     *
     * @dataProvider calculateProvider
     */
    public function testCalculate(
        string $operator,
        string $productStatusUuid,
        array $searchedStatusIds,
        string $languages,
        bool $expectedResult
    ): void {
        $product = $this->createProductMock('some-id');

        $product
            ->method('hasAttribute')
            ->willReturn(true);

        $product
            ->method('getAttribute')
            ->willReturn(new TranslatableStringValue(new TranslatableString([$languages => $productStatusUuid])));

        $condition = $this->createProductHasStatusConditionMock(
            $operator,
            $searchedStatusIds,
            new Language($languages)
        );
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
                '21657757-ab97-4f04-b930-e243d19dae82',
                ['21657757-ab97-4f04-b930-e243d19dae82', '1a71c295-55a2-4ab7-9b77-ebffecc8776d'],
                'en_GB',
                true,
            ],
            'HAS false' => [
                'HAS',
                '21657757-ab97-4f04-b930-e243d19dae82',
                ['1a71c295-55a2-4ab7-9b77-ebffecc8776d', '0f8a1750-5491-4532-bbfa-12cc7406694b'],
                'en_GB',
                false,
            ],
            'NOT_HAS false' => [
                'NOT_HAS',
                '21657757-ab97-4f04-b930-e243d19dae82',
                ['21657757-ab97-4f04-b930-e243d19dae82', '1a71c295-55a2-4ab7-9b77-ebffecc8776d'],
                'en_GB',
                false,
            ],
            'NOT_HAS true' => [
                'NOT_HAS',
                '21657757-ab97-4f04-b930-e243d19dae82',
                ['1a71c295-55a2-4ab7-9b77-ebffecc8776d', '0f8a1750-5491-4532-bbfa-12cc7406694b'],
                'en_EN',
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

    private function createProductHasStatusConditionMock(
        string $operator,
        array $searchedStatuses,
        Language $language
    ): MockObject {
        $mock = $this->createMock(ProductHasStatusCondition::class);
        $mock->method('getOperator')->willReturn($operator);
        foreach ($searchedStatuses as $searchedStatus) {
            $searchedStatusIds[] = new StatusId($searchedStatus);
        }
        $mock->method('getValue')->willReturn($searchedStatusIds);
        $mock->method('getLanguage')->willReturn([$language]);

        return $mock;
    }
}
