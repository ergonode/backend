<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Provider;

use Ergonode\Attribute\Domain\AttributeUpdaterInterface;
use Ergonode\Attribute\Domain\Provider\AttributeUpdaterProvider;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeUpdateProviderTest extends TestCase
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
        /** @var AttributeUpdaterInterface|MockObject $factory */
        $factory = $this->createMock(AttributeUpdaterInterface::class);
        $factory->method('isSupported')->willReturn(true);

        $provider = new AttributeUpdaterProvider(...[$factory]);
        $result = $provider->provide($this->type);
        $this->assertEquals($factory, $result);
    }

    /**
     */
    public function testFalseProvideAttributeFactory(): void
    {
        $this->expectException(\RuntimeException::class);
        /** @var AttributeUpdaterInterface|MockObject $factory */
        $factory = $this->createMock(AttributeUpdaterInterface::class);
        $factory->method('isSupported')->willReturn(false);

        $provider = new AttributeUpdaterProvider(...[$factory]);
        $provider->provide($this->type);
    }
}
