<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Tests\Infrastructure\Condition\Calculator;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Condition\Domain\Condition\OptionAttributeValueCondition;
use Ergonode\Condition\Infrastructure\Condition\Calculator\OptionAttributeValueConditionCalculatorStrategy;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class OptionAttributeValueConditionCalculatorStrategyTest extends TestCase
{
    /**
     * @var AttributeRepositoryInterface|MockObject
     */
    private $mockAttributeRepository;
    private OptionAttributeValueConditionCalculatorStrategy $calculator;

    protected function setUp(): void
    {
        $this->mockAttributeRepository = $this->createMock(AttributeRepositoryInterface::class);
        $this->calculator = new OptionAttributeValueConditionCalculatorStrategy(
            $this->mockAttributeRepository,
        );
    }

    /**
     * @dataProvider calculateCasesProvider
     *
     * @param string[] $values
     */
    public function testCalculate(string $expectedValue, array $values, bool $calculated): void
    {
        $configuration = new OptionAttributeValueCondition(
            new AttributeId((string) Uuid::uuid4()),
            $expectedValue,
        );
        $product = $this->createMock(AbstractProduct::class);
        $attribute = $this->createMock(AbstractAttribute::class);
        $this->mockAttributeRepository
            ->method('load')
            ->willReturn(
                $attribute,
            );
        $product
            ->method('hasAttribute')
            ->willReturn(true);
        $value = $this->createMock(ValueInterface::class);
        $product
            ->method('getAttribute')
            ->willReturn($value);
        $value
            ->method('getValue')
            ->willReturn($values);

        $result = $this->calculator->calculate($product, $configuration);

        $this->assertEquals($calculated, $result);
    }

    public function testCalculateReturnFalseWhenProductHasNoAttribute(): void
    {
        $configuration = new OptionAttributeValueCondition(
            new AttributeId((string) Uuid::uuid4()),
            'val',
        );
        $product = $this->createMock(AbstractProduct::class);
        $attribute = $this->createMock(AbstractAttribute::class);
        $this->mockAttributeRepository
            ->method('load')
            ->willReturn(
                $attribute,
            );
        $product
            ->method('hasAttribute')
            ->willReturn(false);

        $result = $this->calculator->calculate($product, $configuration);

        $this->assertFalse($result);
    }

    public function testShouldThrowExceptionWhenNoAttribute(): void
    {
        $configuration = new OptionAttributeValueCondition(
            new AttributeId((string) Uuid::uuid4()),
            'val',
        );
        $product = $this->createMock(AbstractProduct::class);
        $this->mockAttributeRepository
            ->method('load')
            ->willReturn(null);
        $product
            ->method('hasAttribute')
            ->willReturn(false);

        $this->expectException(\InvalidArgumentException::class);

        $this->calculator->calculate($product, $configuration);
    }

    /**
     * @return mixed[]
     */
    public function calculateCasesProvider(): array
    {
        return [
            ['val1', ['en_GB' => 'val2', 'pl_PL' => 'val1'], true],
            ['val3', ['en_GB' => 'val2', 'pl_PL' => 'val1'], false],
            ['val1', ['en_GB' => 'val2,val3', 'pl_PL' => 'val4,val1'], true],
            ['val3', ['en_GB' => 'val2,val3', 'pl_PL' => 'val4,val1'], true],
            ['val5', ['en_GB' => 'val2,val3', 'pl_PL' => 'val4,val1'], false],
        ];
    }
}
