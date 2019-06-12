<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Transformer\Tests\Infrastructure\Converter;

use Ergonode\Transformer\Infrastructure\Converter\JoinConverter;
use PHPUnit\Framework\TestCase;

/**
 * Class TextConverterTest
 */
class JoinConverterTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @param string $pattern
     * @param string $exception
     */
    public function testConverter(string $pattern, string $exception): void
    {
        $record = $this->getRecord();

        $converter = new JoinConverter($pattern);

        $result = $converter->map($record, '');
        $this->assertEquals($exception, $result->getValue());
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'phrase' => 'Joined Phrase <first> <second>',
                'result' => 'Joined Phrase alpha beta',
            ],
            [
                'phrase' => 'Joined Phrase <first> <third>',
                'result' => 'Joined Phrase alpha gamma',
            ],
            [
                'phrase' => '<third> Joined Phrase <second>',
                'result' => 'gamma Joined Phrase beta',
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
