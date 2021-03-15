<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Tests\Domain\Service\Strategy;

use Ergonode\Value\Domain\Service\Strategy\StringValueUpdateStrategy;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use PHPUnit\Framework\TestCase;

class StringValueUpdateStrategyTest extends TestCase
{
    public function testSupported(): void
    {
        $strategy = new StringValueUpdateStrategy();
        $invalid = $this->createMock(StringCollectionValue::class);
        $valid = $this->createMock(StringValue::class);

        $this->assertTrue($strategy->isSupported($valid));
        $this->assertFalse($strategy->isSupported($invalid));
    }

    public function testCalculate(): void
    {
        $strategy = new StringValueUpdateStrategy();
        $newValue = $this->createMock(StringValue::class);
        $newValue->method('getValue')->willReturn(['test1']);
        $oldValue = $this->createMock(StringValue::class);

        $calculated = $strategy->calculate($oldValue, $newValue);

        $this->assertEquals($calculated->getValue(), ['test1']);
    }

    public function testWrongOldValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $strategy = new StringValueUpdateStrategy();
        $invalid = $this->createMock(StringCollectionValue::class);
        $newValue = $this->createMock(StringValue::class);

        $strategy->calculate($invalid, $newValue);
    }

    public function testWrongNewValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $strategy = new StringValueUpdateStrategy();
        $invalid = $this->createMock(StringCollectionValue::class);
        $oldValue = $this->createMock(StringValue::class);

        $strategy->calculate($oldValue, $invalid);
    }
}
