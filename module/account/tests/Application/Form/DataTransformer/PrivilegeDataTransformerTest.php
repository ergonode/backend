<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Account\Application\Form\DataTransformer;

use Ergonode\Account\Application\Form\DataTransformer\PrivilegeDataTransformer;
use Ergonode\Account\Domain\ValueObject\Privilege;
use PHPUnit\Framework\TestCase;

/**
 */
class PrivilegeDataTransformerTest extends TestCase
{

    /**
     * @var PrivilegeDataTransformer
     */
    protected $transformer;

    protected function setUp()
    {
        $this->transformer = new PrivilegeDataTransformer();
    }

    /**
     * @param Privilege|null $privilegeValueObject
     * @param string|null    $string
     *
     * @dataProvider dataProvider
     */
    public function testTransform(?Privilege $privilegeValueObject, ?string $string): void
    {
        $this->assertSame($string, $this->transformer->transform($privilegeValueObject));
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     * @expectedExceptionMessage Invalid Privilege object
     */
    public function testTransformException(): void
    {
        $value = new \stdClass();
        $this->transformer->transform($value);
    }


    /**
     * @param Privilege|null $privilegeValueObject
     * @param string|null    $string
     *
     * @dataProvider dataProvider
     */
    public function testReverseTransform(?Privilege $privilegeValueObject, ?string $string): void
    {
        $this->assertEquals($privilegeValueObject, $this->transformer->reverseTransform($string));
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     * @expectedExceptionMessage Invalid Privilege "7zmwvoa60el83MuQ2L5o4RgoJf3eGj6dWZDC30pTVAAPHLAqYKPbWyRtb2szH5PLV6X4euonbgyuTERSjzG6gmL2g8SI9q7PICFjMf1k4Slizle3DoTWv4re4OdQRQ6qo8" value
     */
    public function testReverseTransformException(): void
    {
        $value = '7zmwvoa60el83MuQ2L5o4RgoJf3eGj6dWZDC30pTVAAPHLAqYKPbWyRtb2szH5PLV6X4euonbgyuTERSjzG6gmL2g8SI9q7PICFjMf1k4Slizle3DoTWv4re4OdQRQ6qo8';
        $this->transformer->reverseTransform($value);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                'privilegeValueObject' => new Privilege('PRIVELEGE_EXAMPLE'),
                'string' => 'PRIVELEGE_EXAMPLE',
            ],
            [
                'privilegeValueObject' => null,
                'string' => null,
            ],
        ];
    }
}
