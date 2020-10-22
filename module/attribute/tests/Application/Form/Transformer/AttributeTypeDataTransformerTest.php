<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Application\Form\Transformer;

use Ergonode\Attribute\Application\Form\Transformer\AttributeTypeDataTransformer;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use PHPUnit\Framework\TestCase;

class AttributeTypeDataTransformerTest extends TestCase
{
    protected AttributeTypeDataTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new AttributeTypeDataTransformer();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testTransform(?AttributeType $attributeTypeValueObject, ?string $string): void
    {
        self::assertSame($string, $this->transformer->transform($attributeTypeValueObject));
    }

    public function testTransformException(): void
    {
        $this->expectException(\Symfony\Component\Form\Exception\TransformationFailedException::class);
        $this->expectExceptionMessage("Invalid AttributeType object");
        $value = new \stdClass();
        $this->transformer->transform($value);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testReverseTransform(?AttributeType $attributeTypeValueObject, ?string $string): void
    {
        self::assertEquals($attributeTypeValueObject, $this->transformer->reverseTransform($string));
    }

    public function testReverseTransformException(): void
    {
        $this->expectException(\Symfony\Component\Form\Exception\TransformationFailedException::class);
        $value = ['foo', 'bar'];
        $this->transformer->reverseTransform($value);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'attributeTypeValueObject' => new AttributeType('en_GB'),
                'string' => 'en_GB',
            ],
            [
                'attributeTypeValueObject' => null,
                'string' => null,
            ],
        ];
    }
}
