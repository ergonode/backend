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
     */
    public function testValueCreation(): void
    {
        $format = 'jpg';

        $valueObject = new ImageFormat($format);

        $this->assertSame($format, $valueObject->getFormat());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNotValidFormat(): void
    {
        $format = 'format';

        $valueObject = new ImageFormat($format);
    }
}
