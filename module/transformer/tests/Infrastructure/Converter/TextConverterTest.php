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
     * @dataProvider dataProvider
     *
     * @param string $field
     * @param string $exception
     */
    public function testConverter(string $field, string $exception): void
    {
        $record = $this->getRecord();
        $converter = new TextConverter();

        $result = $converter->map($record, $field);
        $this->assertEquals($exception, $result->getValue());
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'field' => 'first',
                'result' => 'alpha',
            ],
            [
                'field' => 'second',
                'result' => 'beta',
            ],
            [
                'field' => 'third',
                'result' => 'gamma',
            ],
        ];
    }

    /**
     * @return array
     */
    private function getRecord(): array
    {
        return [
            'first' => 'alpha',
            'second' => 'beta',
            'third' => 'gamma',
        ];
    }
}
