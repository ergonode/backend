<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Tests\Infrastructure\Converter;

use Ergonode\Importer\Infrastructure\Converter\TextConverter;
use PHPUnit\Framework\TestCase;

class TextConverterTest extends TestCase
{
    public function testConverter(): void
    {
        $field = 'Any field name';
        $converter = new TextConverter($field);

        $this->assertEquals($field, $converter->getField());
    }
}
