<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Attribute\Application\Form\Transformer;

use Ergonode\Attribute\Application\Form\Transformer\AttributeTypeDataTransformer;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeTypeDataTransformerTest extends TestCase
{

    protected $transformer;

    protected function setUp()
    {
        $this->transformer = new AttributeTypeDataTransformer();
    }

    /**
     * @param AttributeType|null $attributeTypeValueObject
     * @param string|null        $string
     *
     * @dataProvider dataProvider
     */
    public function testTransform(?AttributeType $attributeTypeValueObject, ?string $string): void
    {
        $this->assertSame($string, $this->transformer->transform($attributeTypeValueObject));
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     * @expectedExceptionMessage Invalid AttributeType object
     */
    public function testTransformException(): void
    {
        $value = new \stdClass();
        $this->transformer->transform($value);
    }


    /**
     * @param AttributeType|null $attributeTypeValueObject
     * @param string|null        $string
     *
     * @dataProvider dataProvider
     */
    public function testReverseTransform(?AttributeType $attributeTypeValueObject, ?string $string): void
    {
        $this->assertEquals($attributeTypeValueObject, $this->transformer->reverseTransform($string));
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'attributeTypeValueObject' => new AttributeType('EN'),
                'string' => 'EN',
            ],
            [
                'attributeTypeValueObject' => null,
                'string' => null,
            ],
        ];
    }
}
