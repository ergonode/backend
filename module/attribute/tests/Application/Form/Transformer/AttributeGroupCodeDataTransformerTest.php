<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Application\Form\Transformer;

use Ergonode\Attribute\Application\Form\Transformer\AttributeGroupCodeDataTransformer;
use Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use PHPUnit\Framework\TestCase;

class AttributeGroupCodeDataTransformerTest extends TestCase
{
    /**
     * @var AttributeGroupCodeDataTransformer
     */
    protected $transformer;

    /**
     */
    protected function setUp()
    {
        $this->transformer = new AttributeGroupCodeDataTransformer();
    }

    /**
     * @param AttributeGroupCode|null $code
     * @param string|null             $string
     *
     * @dataProvider dataProvider
     */
    public function testTransform(?AttributeGroupCode $code, ?string $string): void
    {
        $this->assertSame($string, $this->transformer->transform($code));
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function testTransformException(): void
    {
        $value = new \stdClass();
        $this->transformer->transform($value);
    }

    /**
     * @param AttributeGroupCode|null $code
     * @param string|null             $string
     *
     * @dataProvider dataProvider
     */
    public function testReverseTransform(?AttributeGroupCode $code, ?string $string): void
    {
        $this->assertEquals($code, $this->transformer->reverseTransform($string));
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'attributeTypeValueObject' => new AttributeGroupCode('EN'),
                'string' => 'en',
            ],
            [
                'attributeTypeValueObject' => null,
                'string' => null,
            ],
        ];
    }
}
