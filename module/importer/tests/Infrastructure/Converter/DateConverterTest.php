<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Tests\Infrastructure\Converter;

use Ergonode\Importer\Infrastructure\Converter\DateConverter;
use PHPUnit\Framework\TestCase;

class DateConverterTest extends TestCase
{
    public function testConverterCreation(): void
    {
        $field = 'Any field name';
        $format = 'Y-m-d';
        $converter = new DateConverter($field, $format);
        $this->assertSame($field, $converter->getField());
        $this->assertSame($format, $converter->getFormat());
    }
}
