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
    protected SizeFormDataTransformer $transformer;

    /**
     */
    protected function setUp(): void
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
     *
     */
    public function testTransformException(): void
    {
        $this->expectException(\Symfony\Component\Form\Exception\TransformationFailedException::class);
        $this->expectExceptionMessage("Invalid Size object");
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
     *
     */
    public function testReverseTransformException(): void
    {
        $this->expectExceptionMessage('invalid size -1,-2 value');
        $this->expectException(\Symfony\Component\Form\Exception\TransformationFailedException::class);
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
