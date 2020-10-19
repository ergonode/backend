<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Tests\Domain\Service\Strategy;

use Ergonode\Value\Domain\Service\Strategy\StringCollectionValueUpdateStrategy;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use PHPUnit\Framework\TestCase;

/**
 */
class StringCollectionValueUpdateStrategyTest extends TestCase
{
    /**
     */
    public function testSupported(): void
    {
        $strategy = new StringCollectionValueUpdateStrategy();
        $valid = $this->createMock(StringCollectionValue::class);
        $invalid = $this->createMock(StringValue::class);

        self::assertTrue($strategy->isSupported($valid));
        self::assertFalse($strategy->isSupported($invalid));
    }

    /**
     */
    public function testCalculate(): void
    {
        $strategy = new StringCollectionValueUpdateStrategy();
        $newValue = $this->createMock(StringCollectionValue::class);
        $newValue->method('getValue')->willReturn(['test1']);
        $oldValue = $this->createMock(StringCollectionValue::class);
        $oldValue->method('getValue')->willReturn(['test2']);

        $calculated = $strategy->calculate($oldValue, $newValue);

        self::assertEquals($calculated->getValue(), ['test2', 'test1']);
    }

    /**
     */
    public function testWrongOldValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $strategy = new StringCollectionValueUpdateStrategy();
        $invalid = $this->createMock(StringValue::class);
        $newValue = $this->createMock(StringCollectionValue::class);

        $strategy->calculate($invalid, $newValue);
    }

    /**
     */
    public function testWrongNewValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $strategy = new StringCollectionValueUpdateStrategy();
        $invalid = $this->createMock(StringValue::class);
        $oldValue = $this->createMock(StringCollectionValue::class);

        $strategy->calculate($oldValue, $invalid);
    }
}
