<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Tests\Domain\Service\Strategy;

use Ergonode\Value\Domain\Service\Strategy\TranslatableStringValueUpdateStrategy;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use PHPUnit\Framework\TestCase;

class TranslatableStringValueUpdateStrategyTest extends TestCase
{
    public function testSupported(): void
    {
        $strategy = new TranslatableStringValueUpdateStrategy();
        $valid = $this->createMock(TranslatableStringValue::class);
        $invalid = $this->createMock(StringValue::class);

        self::assertTrue($strategy->isSupported($valid));
        self::assertFalse($strategy->isSupported($invalid));
    }

    public function testCalculate(): void
    {
        $strategy = new TranslatableStringValueUpdateStrategy();
        $newValue = $this->createMock(TranslatableStringValue::class);
        $newValue->method('getValue')->willReturn(['en_GB' => 'test1']);
        $oldValue = $this->createMock(TranslatableStringValue::class);
        $oldValue->method('getValue')->willReturn(['en_GB' => 'test2']);

        $calculated = $strategy->calculate($oldValue, $newValue);

        self::assertEquals($calculated->getValue(), ['en_GB' => 'test1']);
    }

    public function testWrongOldValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $strategy = new TranslatableStringValueUpdateStrategy();
        $invalid = $this->createMock(StringValue::class);
        $newValue = $this->createMock(TranslatableStringValue::class);

        $strategy->calculate($invalid, $newValue);
    }

    public function testWrongNewValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $strategy = new TranslatableStringValueUpdateStrategy();
        $invalid = $this->createMock(StringValue::class);
        $oldValue = $this->createMock(TranslatableStringValue::class);

        $strategy->calculate($oldValue, $invalid);
    }
}
