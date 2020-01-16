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
    protected $transformer;

    /**
     */
    protected function setUp()
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
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     *
     * @expectedExceptionMessage Invalid Position object
     */
    public function testTransformException(): void
    {
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
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     *
     * @expectedExceptionMessage Invalid Position -1,-2 value
     */
    public function testReverseTransformException(): void
    {
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
