<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Infrastructure\Mapper;

use Ergonode\Attribute\Infrastructure\Mapper\AttributeValueMapper;
use PHPUnit\Framework\TestCase;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Infrastructure\Mapper\Strategy\AttributeMapperStrategyInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

class AttributeValueMapperTest extends TestCase
{
    private AttributeType $type;

    private AttributeMapperStrategyInterface $strategy;

    protected function setUp(): void
    {
        $this->type = $this->createMock(AttributeType::class);
        $this->strategy = $this->createMock(AttributeMapperStrategyInterface::class);
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
        $mapper->map($this->type, []);
    }

    public function testNotFoundStrategy(): void
    {
        $this->strategy->expects(self::once())->method('supported')->willReturn(false);
        $this->expectException(\RuntimeException::class);

        $mapper = new AttributeValueMapper([$this->strategy]);
        $mapper->map($this->type, []);
    }

    public function testMapStrategy(): void
    {
        $result = $this->createMock(ValueInterface::class);

        $this->strategy->expects(self::once())->method('supported')->willReturn(true);
        $this->strategy->expects(self::once())->method('map')->willReturn($result);

        $mapper = new AttributeValueMapper([$this->strategy]);
        $value = $mapper->map($this->type, []);

        self::assertSame($result, $value);
    }
}
