<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Core\Application\Form\DataTransformer;

use Ergonode\Core\Application\Form\DataTransformer\LanguageDataTransformer;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\TestCase;

/**
 */
class LanguageDataTransformerTest extends TestCase
{

    /**
     * @var LanguageDataTransformer
     */
    protected $transformer;

    /**
     */
    protected function setUp()
    {
        $this->transformer = new LanguageDataTransformer();
    }

    /**
     * @param Language|null $languageValueObject
     * @param string|null   $string
     *
     * @dataProvider dataProvider
     */
    public function testTransform(?Language $languageValueObject, ?string $string): void
    {
        $this->assertSame($string, $this->transformer->transform($languageValueObject));
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     *
     * @expectedExceptionMessage Invalid Language object
     */
    public function testTransformException(): void
    {
        $value = new \stdClass();
        $this->transformer->transform($value);
    }

    /**
     * @param Language|null $languageValueObject
     * @param string|null   $string
     *
     * @dataProvider dataProvider
     */
    public function testReverseTransform(?Language $languageValueObject, ?string $string): void
    {
        $this->assertEquals($languageValueObject, $this->transformer->reverseTransform($string));
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     *
     * @expectedExceptionMessage Invalid Language "ZZ" value
     */
    public function testReverseTransformException(): void
    {
        $value = 'ZZ';
        $this->transformer->reverseTransform($value);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'languageValueObject' => new Language('EN'),
                'string' => 'EN',
            ],
            [
                'languageValueObject' => null,
                'string' => null,
            ],
        ];
    }
}
