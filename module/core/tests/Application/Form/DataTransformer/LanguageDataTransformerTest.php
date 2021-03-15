<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Application\Form\DataTransformer;

use Ergonode\Core\Application\Form\DataTransformer\LanguageDataTransformer;
use Ergonode\Core\Domain\ValueObject\Language;
use PHPUnit\Framework\TestCase;

class LanguageDataTransformerTest extends TestCase
{

    protected LanguageDataTransformer $transformer;

    protected function setUp(): void
    {
        $this->transformer = new LanguageDataTransformer();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testTransform(?Language $languageValueObject, ?string $string): void
    {
        self::assertSame($string, $this->transformer->transform($languageValueObject));
    }

    public function testTransformException(): void
    {
        $this->expectException(\Symfony\Component\Form\Exception\TransformationFailedException::class);
        $this->expectExceptionMessage("Invalid Language object");
        $value = new \stdClass();
        $this->transformer->transform($value);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testReverseTransform(?Language $languageValueObject, ?string $string): void
    {
        self::assertEquals($languageValueObject, $this->transformer->reverseTransform($string));
    }

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
                'languageValueObject' => new Language('en_GB'),
                'string' => 'en_GB',
            ],
            [
                'languageValueObject' => null,
                'string' => null,
            ],
        ];
    }
}
