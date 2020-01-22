<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Transformer\Tests\Infrastructure\Converter;

use Ergonode\Transformer\Infrastructure\Converter\TextConverter;
use PHPUnit\Framework\TestCase;

/**
 */
class TextConverterTest extends TestCase
{
    /**
     */
    public function testConverter(): void
    {
        $field = 'Any field name';
        $converter = new TextConverter($field);

        $this->assertEquals($field, $converter->getField());
    }
}
