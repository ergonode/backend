<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Tests\Domain\ValueObject;

use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use PHPUnit\Framework\TestCase;

/**
 */
class SegmentCodeTest extends TestCase
{
    /**
     * @param string $string
     *
     * @dataProvider validDataProvider
     */
    public function testValidCreation(string $string): void
    {
        $status = new SegmentCode($string);
        $this->assertEquals($string, $status->getValue());
        $this->assertTrue(SegmentCode::isValid($string));
    }

    /**
     * @param string $status
     *
     * @dataProvider validDataProvider
     */
    public function testPositiveValidation(string $status): void
    {
        $this->assertTrue(SegmentCode::isValid($status));
    }

    /**
     * @param string $status
     *
     * @dataProvider inValidDataProvider
     */
    public function testNegativeValidation(string $status): void
    {
        $this->assertFalse(SegmentCode::isValid($status));
    }

    /**
     * @param string $status
     *
     * @dataProvider inValidDataProvider
     *
     */
    public function testInvalidData(string $status): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new SegmentCode($status);
    }

    /**
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            ['valid code'],
            [str_repeat('a', 100)],

        ];
    }

    /**
     * @return array
     */
    public function inValidDataProvider(): array
    {
        return [
            [''],
            [str_repeat('a', 101)],
        ];
    }
}
