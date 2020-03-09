<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Domain\ValueObject;

use Ergonode\Core\Domain\ValueObject\Color;
use PHPUnit\Framework\TestCase;

/**
 */
class ColorTest extends TestCase
{
    /**
     * @dataProvider validHexColor
     *
     * @param string $hex
     */
    public function testValidColorCreation(string $hex): void
    {
        $color = new Color($hex);
        $this->assertSame(strtoupper($hex), $color->getValue());
        $this->assertSame(strtoupper($hex), (string) $color);
    }

    /**
     * @dataProvider inValidHexColor
     *
     * @param string $hex
     *
     */
    public function testInvalidColorCreation(string $hex): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Color($hex);
    }

    /**
     */
    public function testColorEquality():void
    {
        $color1 = new Color('#000000');
        $color2 = new Color('#000000');
        $color3 = new Color('#ffffff');

        $this->assertTrue($color1->isEqual($color2));
        $this->assertTrue($color2->isEqual($color1));
        $this->assertFalse($color1->isEqual($color3));
        $this->assertFalse($color2->isEqual($color3));
        $this->assertFalse($color3->isEqual($color1));
        $this->assertFalse($color3->isEqual($color2));
    }

    /**
     * @return array
     */
    public function validHexColor(): array
    {
        return [
            ['#000000'],
            ['#FF0000'],
            ['#00FF00'],
            ['#0000FF'],
            ['#000000'],
            ['#ff0000'],
            ['#00ff00'],
            ['#0000ff'],
        ];
    }

    /**
     * @return array
     */
    public function invalidHexColor(): array
    {
        return [
            ['000000'],
            [''],
            ['OOPPFF'],
            ['#00000000'],
            ['RGB'],
            ['COLOR'],
        ];
    }
}
