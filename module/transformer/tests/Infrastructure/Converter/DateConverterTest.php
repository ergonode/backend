<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Transformer\Tests\Infrastructure\Converter;

use Ergonode\Transformer\Infrastructure\Converter\DateConverter;
use PHPUnit\Framework\TestCase;

/**
 */
class DateConverterTest extends TestCase
{
    /**
     */
    public function testConverterCreation(): void
    {
        $field = 'Any field name';
        $format = 'Y-m-d';
        $converter = new DateConverter($field, $format);
        self::assertSame($field, $converter->getField());
        self::assertSame($format, $converter->getFormat());
    }
}
