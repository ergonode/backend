<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Tests\Domain\ValueObject;

use Ergonode\Multimedia\Domain\ValueObject\ImageFormat;
use PHPUnit\Framework\TestCase;

/**
 */
class ImageFormatTest extends TestCase
{
    /**
     * @param $format
     *
     * @dataProvider dataProvider
     */
    public function testValueCreation($format): void
    {

        $valueObject = new ImageFormat($format);

        $this->assertSame($format, $valueObject->getFormat());
    }

    /**
     */
    public function testNotValidFormat(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $format = 'format';

        $valueObject = new ImageFormat($format);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            ['format' => 'jpg'],
            ['format' => 'jpeg'],
            ['format' => 'gif'],
            ['format' => 'tiff'],
            ['format' => 'tif'],
            ['format' => 'png'],
            ['format' => 'bmp'],
        ];
    }
}
