<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Account\Application\Form\DataTransformer;

use Ergonode\Account\Application\Form\DataTransformer\RoleIdDataTransformer;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;
use PHPUnit\Framework\TestCase;

/**
 */
class RoleIdDataTransformerTest extends TestCase
{

    /**
     * @var RoleIdDataTransformer
     */
    protected RoleIdDataTransformer $transformer;

    /**
     */
    protected function setUp(): void
    {
        $this->transformer = new RoleIdDataTransformer();
    }

    /**
     * @param RoleId|null $roleIdValueObject
     * @param string|null $string
     *
     * @dataProvider dataProvider
     */
    public function testTransform(?RoleId $roleIdValueObject, ?string $string): void
    {
        $this->assertSame($string, $this->transformer->transform($roleIdValueObject));
    }

    /**
     *
     */
    public function testTransformException(): void
    {
        $this->expectException(\Symfony\Component\Form\Exception\TransformationFailedException::class);
        $this->expectExceptionMessage("Invalid RoleId object");
        $value = new \stdClass();
        $this->transformer->transform($value);
    }


    /**
     * @param RoleId|null $roleIdValueObject
     * @param string|null $string
     *
     * @dataProvider dataProvider
     */
    public function testReverseTransform(?RoleId $roleIdValueObject, ?string $string): void
    {
        $this->assertEquals($roleIdValueObject, $this->transformer->reverseTransform($string));
    }

    /**
     */
    public function testReverseTransformException(): void
    {
        $this->expectExceptionMessage('Invalid "not_uuid" value');
        $this->expectException(\Symfony\Component\Form\Exception\TransformationFailedException::class);
        $value = 'not_uuid';
        $this->transformer->reverseTransform($value);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'roleIdValueObject' => new RoleId('e2ef4d2e-4a0d-4ca1-83c9-e639e586ed1b'),
                'string' => 'e2ef4d2e-4a0d-4ca1-83c9-e639e586ed1b',
            ],
            [
                'roleIdValueObject' => null,
                'string' => null,
            ],
        ];
    }
}
