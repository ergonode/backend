<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Account\Application\Form\DataTransformer;

use Ergonode\Account\Application\Form\DataTransformer\PasswordDataTransformer;
use Ergonode\Account\Domain\ValueObject\Password;
use PHPUnit\Framework\TestCase;

/**
 */
class PasswordDataTransformerTest extends TestCase
{
    /**
     * @var PasswordDataTransformer
     */
    protected $transformer;

    protected function setUp()
    {
        $this->transformer = new PasswordDataTransformer();
    }

    /**
     * @param Password|null $passwordValueObject
     * @param string|null   $string
     *
     * @dataProvider dataProvider
     */
    public function testTransform(?Password $passwordValueObject, ?string $string): void
    {
        $this->assertSame($string, $this->transformer->transform($passwordValueObject));
    }

    /**
     * @param Password|null $passwordValueObject
     * @param string|null   $string
     *
     * @dataProvider dataProvider
     */
    public function testReverseTransform(?Password $passwordValueObject, ?string $string): void
    {
        $this->assertEquals($passwordValueObject, $this->transformer->reverseTransform($string));
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     * @expectedExceptionMessage Invalid "pass" value
     */
    public function testReverseTransformException(): void
    {
        $value = 'pass';
        $this->transformer->reverseTransform($value);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'passwordValueObject' => new Password('Password123'),
                'string' => 'Password123',
            ],
            [
                'passwordValueObject' => null,
                'string' => null,
            ],
        ];
    }
}
