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
    protected LanguageDataTransformer $transformer;

    /**
     */
    protected function setUp(): void
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
     *
     */
    public function testTransformException(): void
    {
        $this->expectException(\Symfony\Component\Form\Exception\TransformationFailedException::class);
        $this->expectExceptionMessage("Invalid Language object");
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
     */
    public function testReverseTransformException(): void
    {
        $this->expectExceptionMessage('Invalid Language "ZZ" value');
        $this->expectException(\Symfony\Component\Form\Exception\TransformationFailedException::class);
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
