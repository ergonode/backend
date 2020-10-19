<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Transformer\Tests\Infrastructure\Converter;

use Ergonode\Transformer\Infrastructure\Converter\JoinConverter;
use PHPUnit\Framework\TestCase;

/**
 */
class JoinConverterTest extends TestCase
{
    /**
     */
    public function testConverterCreation(): void
    {
        $pattern = 'Joined Phrase <first> <second>';

        $converter = new JoinConverter($pattern);

        self::assertEquals($pattern, $converter->getPattern());
    }
}
