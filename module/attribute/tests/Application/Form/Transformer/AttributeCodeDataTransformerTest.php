<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Attribute\Application\Form\Transformer;

use Ergonode\Attribute\Application\Form\Transformer\AttributeCodeDataTransformer;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use PHPUnit\Framework\TestCase;

/**
 */
class AttributeCodeDataTransformerTest extends TestCase
{

    /**
     * @var AttributeCodeDataTransformer
     */
    protected AttributeCodeDataTransformer $transformer;

    /**
     */
    protected function setUp(): void
    {
        $this->transformer = new AttributeCodeDataTransformer();
    }

    /**
     * @param AttributeCode|null $attributeCodeValueObject
     * @param string|null        $string
     *
     * @dataProvider dataProvider
     */
    public function testTransform(?AttributeCode $attributeCodeValueObject, ?string $string): void
    {
        $this->assertSame($string, $this->transformer->transform($attributeCodeValueObject));
    }

    /**
     *
     */
    public function testTransformException(): void
    {
        $this->expectException(\Symfony\Component\Form\Exception\TransformationFailedException::class);
        $this->expectExceptionMessage("Invalid AttributeCode object");
        $value = new \stdClass();
        $this->transformer->transform($value);
    }

    /**
     * @param AttributeCode|null $attributeCodeValueObject
     * @param string|null        $string
     *
     * @dataProvider dataProvider
     */
    public function testReverseTransform(?AttributeCode $attributeCodeValueObject, ?string $string): void
    {
        $this->assertEquals($attributeCodeValueObject, $this->transformer->reverseTransform($string));
    }

    /**
     *
     */
    public function testReverseTransformException(): void
    {
        $this->expectExceptionMessage('Invalid attribute code color/col value');
        $this->expectException(\Symfony\Component\Form\Exception\TransformationFailedException::class);
        $value = 'color/col';
        $this->transformer->reverseTransform($value);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'attributeCodeValueObject' => new AttributeCode('color'),
                'string' => 'color',
            ],
            [
                'attributeCodeValueObject' => null,
                'string' => null,
            ],
        ];
    }
}
