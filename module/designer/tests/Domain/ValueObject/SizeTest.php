<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Designer\Tests\Domain\ValueObject;

use Ergonode\Designer\Domain\ValueObject\Size;
use PHPUnit\Framework\TestCase;

/**
 */
class SizeTest extends TestCase
{
    /**
     * @param int $width
     * @param int $height
     *
     * @dataProvider getCorrectDataProvider
     */
    public function testGreaterOrEqualThenZeroValue(int $width, int $height): void
    {
        $position = new Size($width, $height);
        $this->assertSame($width, $position->getWidth());
        $this->assertSame($height, $position->getHeight());
    }

    /**
     * @param int $width
     * @param int $height
     *
     * @dataProvider getCorrectDataProvider
     */
    public function testGreaterThenZeroValue(int $width, int $height): void
    {
        $position = new Size($width, $height);
        $this->assertSame($width, $position->getWidth());
        $this->assertSame($height, $position->getHeight());
    }

    /**
     * @param int $width
     * @param int $height
     *
     * @dataProvider getIncorrectDataProvider
     *
     */
    public function testLessThenZeroValue(int $width, int $height): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Size($width, $height);
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
