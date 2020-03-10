<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Tests\Application\Form\DataTransformer;

use Ergonode\Core\Application\Form\DataTransformer\BooleanDataTransformer;
use PHPUnit\Framework\TestCase;

/**
 */
class BooleanDataTransformerTest extends TestCase
{

    /**
     * @dataProvider transformDataProvider
     *
     * @param mixed $value
     * @param mixed $expected
     */
    public function testTransform($value, $expected): void
    {
        $transformer = new BooleanDataTransformer();
        $this->assertEquals($expected, $transformer->transform($value));
    }

    /**
     * @dataProvider  reverseDataProvider
     *
     * @param mixed  $value
     * @param string $expected
     */
    public function testReverseTransform($value, string $expected): void
    {
        $transformer = new BooleanDataTransformer();
        $this->assertEquals($expected, $transformer->reverseTransform($value));
    }

    /**
     */
    public function testReverseTransformException(): void
    {
        $transformer = new BooleanDataTransformer();
        $this->expectExceptionMessage('Expect boolean');
        $this->assertEquals('false', $transformer->reverseTransform('fadwwalse'));
    }


    /**
     * @return array
     */
    public function reverseDataProvider(): array
    {
        return [
            [
                'value' => 'true',
                'expected' => 'true',
            ],
            [
                'value' => true,
                'expected' => 'true',
            ],
            [
                'value' => 1,
                'expected' => 'true',
            ],
            [
                'value' => 0,
                'expected' => 'false',
            ],
            [
                'value' => 'false',
                'expected' => 'false',
            ],
            [
                'value' => false,
                'expected' => 'false',
            ],
        ];
    }

    /**
     * @return array
     */
    public function transformDataProvider(): array
    {
        return [
            [
                'value' => 'true',
                'expected' => 1,
            ],
            [
                'value' => 'false',
                'expected' => false,
            ],
        ];
    }
}
