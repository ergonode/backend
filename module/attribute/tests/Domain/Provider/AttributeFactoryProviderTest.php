<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Provider;

use Ergonode\Attribute\Domain\AttributeFactoryInterface;
use Ergonode\Attribute\Domain\Provider\AttributeFactoryProvider;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeFactoryProviderTest extends TestCase
{
    /**
     * AttributeType|MockObject
     */
    private $type;

    /**
     */
    protected function setUp(): void
    {
        $this->type = $this->createMock(AttributeType::class);
        $this->type->method('getValue')->willReturn('ANY_TYPE');
    }

    /**
     */
    public function testProvideAttributeFactory(): void
    {
        /** @var AttributeFactoryInterface|MockObject $factory */
        $factory = $this->createMock(AttributeFactoryInterface::class);
        $factory->method('supports')->willReturn(true);

        $provider = new AttributeFactoryProvider(...[$factory]);
        $result = $provider->provide($this->type);
        $this->assertEquals($factory, $result);
    }

    /**
     */
    public function testFalseProvideAttributeFactory(): void
    {
        $this->expectException(\RuntimeException::class);
        /** @var AttributeFactoryInterface|MockObject $factory */
        $factory = $this->createMock(AttributeFactoryInterface::class);
        $factory->method('supports')->willReturn(false);

        $provider = new AttributeFactoryProvider(...[$factory]);
        $provider->provide($this->type);
    }
}
