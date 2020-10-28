<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Transformer\Tests\Infrastructure\Converter;

use Ergonode\Transformer\Infrastructure\Converter\JoinConverter;
use PHPUnit\Framework\TestCase;

class JoinConverterTest extends TestCase
{
    public function testConverterCreation(): void
    {
        $pattern = 'Joined Phrase <first> <second>';

        $converter = new JoinConverter($pattern);

        $this->assertEquals($pattern, $converter->getPattern());
    }
}
