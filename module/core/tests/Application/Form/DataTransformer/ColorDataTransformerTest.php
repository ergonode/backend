<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Application\Form\DataTransformer;

use Ergonode\Core\Application\Form\DataTransformer\ColorDataTransformer;
use Ergonode\Core\Domain\ValueObject\Color;
use PHPUnit\Framework\TestCase;

class ColorDataTransformerTest extends TestCase
{

    protected ColorDataTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new ColorDataTransformer();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testTransform(?Color $colorValueObject, ?string $string): void
    {
        self::assertSame($string, $this->transformer->transform($colorValueObject));
    }

    public function testTransformException(): void
    {
        $this->expectException(\Symfony\Component\Form\Exception\TransformationFailedException::class);
        $this->expectExceptionMessage("Invalid Color object");
        $value = new \stdClass();
        $this->transformer->transform($value);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testReverseTransform(?Color $colorValueObject, ?string $string): void
    {
        self::assertEquals($colorValueObject, $this->transformer->reverseTransform($string));
    }

    public function testReverseTransformException(): void
    {
        $this->expectExceptionMessage('Invalid Color "black" value');
        $this->expectException(\Symfony\Component\Form\Exception\TransformationFailedException::class);
        $value = 'black';
        $this->transformer->reverseTransform($value);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'colorValueObject' => new Color('#CD5C5C'),
                'string' => '#CD5C5C',
            ],
            [
                'colorValueObject' => null,
                'string' => null,
            ],
        ];
    }
}
