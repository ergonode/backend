<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Attribute\Application\Form\Transformer;

use Ergonode\Attribute\Application\Form\Transformer\OptionKeyDataTransformer;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use PHPUnit\Framework\TestCase;

/**
 */
class OptionKeyDataTransformerTest extends TestCase
{

    /**
     * @var OptionKeyDataTransformer
     */
    protected OptionKeyDataTransformer $transformer;

    /**
     */
    protected function setUp(): void
    {
        $this->transformer = new OptionKeyDataTransformer();
    }

    /**
     * @param OptionKey|null $optionKeyValueObject
     * @param string|null    $string
     *
     * @dataProvider dataProvider
     */
    public function testTransform(?OptionKey $optionKeyValueObject, ?string $string): void
    {
        $this->assertSame($string, $this->transformer->transform($optionKeyValueObject));
    }

    /**
     *
     */
    public function testTransformException(): void
    {
        $this->expectException(\Symfony\Component\Form\Exception\TransformationFailedException::class);
        $this->expectExceptionMessage("Invalid OptionKey object");
        $value = new \stdClass();
        $this->transformer->transform($value);
    }

    /**
     * @param OptionKey|null $optionKeyValueObject
     * @param string|null    $string
     *
     * @dataProvider dataProvider
     */
    public function testReverseTransform(?OptionKey $optionKeyValueObject, ?string $string): void
    {
        $this->assertEquals($optionKeyValueObject, $this->transformer->reverseTransform($string));
    }

    /**
     */
    public function testReverseTransformException(): void
    {
        $this->expectException(\Symfony\Component\Form\Exception\TransformationFailedException::class);
        $value = 'qSYF4E9y2lg10jL5lGAduJw6NqPmeFgZ0e4GeVksl0SpGfvbLmr1OkueTExXYU2Vn3Behf3GaZUPNduEoS0rMJny1uHKWYeGXn9'.
            'Vn2Mv7TJZ3AyHonXnE1Ox5e3ZYSuiXhtTgnTPJk8cR7dLAL2lgWO5OYMNSdmh3w5Tuqs44xXu0DdYDvXj2bhukrfOXVl8PZapcujYo5K'.
            'DIRVeBNIeOHw6zbQv80uUvl73Ul9VH8NQmSqDIcHXarYyZUWDlbmQO6lJ';

        $this->expectExceptionMessage("Invalid Option Key ".$value." value");
        $this->transformer->reverseTransform($value);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'optionKeyValueObject' => new OptionKey('en'),
                'string' => 'en',
            ],
            [
                'optionKeyValueObject' => null,
                'string' => null,
            ],
        ];
    }
}
