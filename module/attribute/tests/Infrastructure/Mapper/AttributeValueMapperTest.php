<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Mapper;

use Ergonode\Attribute\Infrastructure\Mapper\AttributeValueMapper;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Infrastructure\Mapper\Strategy\ContextAwareAttributeMapperStrategyInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

class AttributeValueMapperTest extends TestCase
{
    private AttributeType $type;

    private ContextAwareAttributeMapperStrategyInterface $strategy;

    private ProductId $productId;


    protected function setUp(): void
    {
        $this->type = $this->createMock(AttributeType::class);
        $this->strategy = $this->createMock(ContextAwareAttributeMapperStrategyInterface::class);
        $this->productId = $this->createMock(ProductId::class);
    }

    public function testInvalidClassInjection(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new AttributeValueMapper([new \stdClass()]);
    }

    public function testEmptyInvalidMap(): void
    {
        $this->expectException(\RuntimeException::class);

        $mapper = new AttributeValueMapper([]);
        $mapper->map($this->type, [], $this->productId);
    }

    public function testNotFoundStrategy(): void
    {
        $this->strategy->expects(self::once())->method('supported')->willReturn(false);
        $this->expectException(\RuntimeException::class);

        $mapper = new AttributeValueMapper([$this->strategy]);
        $mapper->map($this->type, [], $this->productId);
    }

    public function testMapStrategy(): void
    {
        $result = $this->createMock(ValueInterface::class);

        $this->strategy->expects(self::once())->method('supported')->willReturn(true);
        $this->strategy->expects(self::once())->method('map')->willReturn($result);

        $mapper = new AttributeValueMapper([$this->strategy]);
        $value = $mapper->map($this->type, [], $this->productId);

        self::assertSame($result, $value);
    }
}
