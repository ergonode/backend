<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\Range;
use PHPUnit\Framework\TestCase;

/**
 */
class RangeTest extends TestCase
{
    /**
     * @param float $min
     * @param float $max
     *
     * @dataProvider dataProvider
     */
    public function testValidRange(float $min, float $max): void
    {
        $range = new Range($min, $max);
        self::assertSame($min, $range->getMin());
        self::assertSame($max, $range->getMax());
    }

    /**
     * @param float $min
     * @param float $max
     *
     * @dataProvider dataProvider
     */
    public function testRangeIsValid(float $min, float $max): void
    {
        self::assertTrue(Range::isValid($min, $max));
        self::assertFalse(Range::isValid($max, $min));
    }

    /**
     * @param float $min
     * @param float $max
     *
     * @dataProvider dataProvider
     */
    public function testRangeEquality(float $min, float $max): void
    {
        $range1 = new Range($min, $max);
        $range2 = new Range($min, $max);

        self::assertTrue($range1->isEqual($range2));
        self::assertTrue($range2->isEqual($range1));
    }

    /**
     * @param float $max
     * @param float $min
     *
     *
     * @dataProvider dataProvider
     */
    public function testInValidRange(float $max, float $min): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Range($min, $max);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [0, 1],
            [0.0, 0.1],
            [-9.9, 9.9],
            [0.0000000000001, 0.0000000000002],
            [-99999999, 99999999],
        ];
    }
}
