<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Account\Application\Form\DataTransformer;

use Ergonode\Account\Application\Form\DataTransformer\RoleIdDataTransformer;
use Ergonode\Account\Domain\Entity\RoleId;
use PHPUnit\Framework\TestCase;

/**
 */
class RoleIdDataTransformerTest extends TestCase
{

    /**
     * @var RoleIdDataTransformer
     */
    protected $transformer;

    /**
     */
    protected function setUp()
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
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     *
     * @expectedExceptionMessage Invalid RoleId object
     */
    public function testTransformException(): void
    {
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
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     *
     * @expectedExceptionMessage Invalid "not_uuid" value
     */
    public function testReverseTransformException(): void
    {
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
