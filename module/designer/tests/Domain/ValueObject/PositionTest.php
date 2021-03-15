<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Tests\Domain\ValueObject;

use Ergonode\Designer\Domain\ValueObject\Position;
use PHPUnit\Framework\TestCase;

class PositionTest extends TestCase
{
    /**
     * @dataProvider getCorrectDataProvider
     */
    public function testGreaterOrEqualThenZeroValue(int $x, int $y): void
    {
        $position = new Position($x, $y);
        $this->assertSame($x, $position->getX());
        $this->assertSame($y, $position->getY());
    }

    /**
     * @dataProvider getCorrectDataProvider
     */
    public function testGreaterThenZeroValue(int $x, int $y): void
    {
        $position = new Position($x, $y);
        $this->assertSame($x, $position->getX());
        $this->assertSame($y, $position->getY());
    }

    /**
     * @dataProvider getIncorrectDataProvider
     */
    public function testLessThenZeroValue(int $x, int $y): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Position($x, $y);
    }

    /**
     * @return array
     */
    public function getCorrectDataProvider(): array
    {
        return [
            [0, 0],
            [0, 1],
            [1, 0],
            [2147483647, 2147483647],
        ];
    }

    /**
     * @return array
     */
    public function getIncorrectDataProvider(): array
    {
        return [
            [-1, 0],
            [0, -1],
            [-2147483647, -2147483647],
        ];
    }
}
