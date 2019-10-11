<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Designer\Application\Form\Transformer;

use Ergonode\Designer\Application\Form\Transformer\SizeFormDataTransformer;
use Ergonode\Designer\Domain\ValueObject\Size;
use PHPUnit\Framework\TestCase;

/**
 */
class SizeFormDataTransformerTest extends TestCase
{

    /**
     * @var SizeFormDataTransformer
     */
    protected $transformer;

    protected function setUp()
    {
        $this->transformer = new SizeFormDataTransformer();
    }

    /**
     * @param Size|null  $sizeValueObject
     * @param array|null $array
     *
     * @dataProvider dataProvider
     */
    public function testTransform(?Size $sizeValueObject, ?array $array): void
    {
        $this->assertSame($array, $this->transformer->transform($sizeValueObject));
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     * @expectedExceptionMessage Invalid Size object
     */
    public function testTransformException(): void
    {
        $value = new \stdClass();
        $this->transformer->transform($value);
    }

    /**
     * @param Size|null  $sizeValueObject
     * @param array|null $array
     *
     * @dataProvider dataProvider
     */
    public function testReverseTransform(?Size $sizeValueObject, ?array $array): void
    {
        $this->assertEquals($sizeValueObject, $this->transformer->reverseTransform($array));
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     * @expectedExceptionMessage invalid size -1,-2 value
     */
    public function testReverseTransformException(): void
    {
        $value = [
            'width' => -1,
            'height' => -2,
        ];
        $this->transformer->reverseTransform($value);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'sizeValueObject' => new Size(2, 3),
                'array' => [
                    'width' => 2,
                    'height' => 3,
                ],
            ],
            [
                'sizeValueObject' => null,
                'array' => null,
            ],
        ];
    }
}
