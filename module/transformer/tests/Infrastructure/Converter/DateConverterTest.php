<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Transformer\Tests\Infrastructure\Converter;

use Ergonode\Transformer\Infrastructure\Converter\DateConverter;
use PHPUnit\Framework\TestCase;

/**
 * Class DateConverterTest
 */
class DateConverterTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @param string $value
     * @param string $format
     * @param string $expected
     *
     * @throws \Ergonode\Transformer\Infrastructure\Exception\ConverterException
     */
    public function testConverter(string $value, string $format, string $expected): void
    {
        $record = ['first' => $value];
        $converter = new DateConverter($format);

        $result = $converter->map($record, 'first');
        $this->assertEquals($expected, $result->getValue());
    }

    /**
     * @expectedException \Ergonode\Transformer\Infrastructure\Exception\ConverterException
     */
    public function testConverterException(): void
    {
        $record = ['first' => 'bad data format'];
        $converter = new DateConverter('Y-m-d');

        $converter->map($record, 'first');
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'value' => '1999-01-01',
                'format' => 'Y-m-d',
                'expected' => '1999-01-01',
            ],
            [
                'value' => '02.03.1992',
                'format' => 'Y-m-d',
                'expected' => '1992-03-02',
            ],
            [
                'value' => '15-03-02',
                'format' => 'Y-m-d',
                'expected' => '2015-03-02',
            ],
            [
                'value' => 'july 1st, 2008',
                'format' => 'Y-m-d',
                'expected' => '2008-07-01',
            ],
        ];
    }
}
