<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Designer\Application\Form\Transformer;

use Ergonode\Designer\Application\Form\Transformer\PositionFormDataTransformer;
use Ergonode\Designer\Domain\ValueObject\Position;
use PHPUnit\Framework\TestCase;

/**
 */
class PositionFormDataTransformerTest extends TestCase
{

    /**
     * @var PositionFormDataTransformer
     */
    protected PositionFormDataTransformer $transformer;

    /**
     */
    protected function setUp(): void
    {
        $this->transformer = new PositionFormDataTransformer();
    }

    /**
     * @param Position|null $positionValueObject
     * @param array|null    $array
     *
     * @dataProvider dataProvider
     */
    public function testTransform(?Position $positionValueObject, ?array $array): void
    {
        $this->assertSame($array, $this->transformer->transform($positionValueObject));
    }

    /**
     *
     */
    public function testTransformException(): void
    {
        $this->expectException(\Symfony\Component\Form\Exception\TransformationFailedException::class);
        $this->expectExceptionMessage("Invalid Position object");
        $value = new \stdClass();
        $this->transformer->transform($value);
    }

    /**
     * @param Position|null $positionValueObject
     * @param array|null    $array
     *
     * @dataProvider dataProvider
     */
    public function testReverseTransform(?Position $positionValueObject, ?array $array): void
    {
        $this->assertEquals($positionValueObject, $this->transformer->reverseTransform($array));
    }

    /**
     *
     */
    public function testReverseTransformException(): void
    {
        $this->expectExceptionMessage('Invalid Position -1,-2 value');
        $this->expectException(\Symfony\Component\Form\Exception\TransformationFailedException::class);
        $value = [
            'x' => -1,
            'y' => -2,
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
                'positionValueObject' => new Position(2, 3),
                'array' => [
                    'x' => 2,
                    'y' => 3,
                ],
            ],
            [
                'positionValueObject' => null,
                'array' => null,
            ],
        ];
    }
}
