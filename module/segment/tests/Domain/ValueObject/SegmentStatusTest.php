<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Tests\Domain\ValueObject;

use Ergonode\Segment\Domain\ValueObject\SegmentStatus;
use PHPUnit\Framework\TestCase;

/**
 */
class SegmentStatusTest extends TestCase
{
    /**
     * @param string $string
     * @param bool   $new
     * @param bool   $processed
     * @param bool   $calculated
     * @param bool   $outdated
     *
     * @dataProvider validDataProvider
     */
    public function testValidStatus(string $string, bool $new, bool $processed, bool $calculated, bool $outdated): void
    {
        $status = new SegmentStatus($string);
        $this->assertEquals(strtoupper($string), (string) $string);
        $this->assertTrue(SegmentStatus::isValid($string));
        $this->assertEquals($new, $status->isNew());
        $this->assertEquals($processed, $status->isProcessed());
        $this->assertEquals($calculated, $status->isCalculated());
        $this->assertEquals($outdated, $status->isOutdated());
    }

    /**
     * @param string $status
     *
     * @dataProvider validDataProvider
     */
    public function testPositiveValidation(string $status): void
    {
        $this->assertTrue(SegmentStatus::isValid($status));
    }

    /**
     * @param string $status
     *
     * @dataProvider inValidDataProvider
     */
    public function testNegativeValidation(string $status): void
    {
        $this->assertFalse(SegmentStatus::isValid($status));
    }

    /**
     * @param string $status
     *
     * @dataProvider inValidDataProvider
     *
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidData(string $status): void
    {
        new SegmentStatus($status);
    }

    /**
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            [SegmentStatus::NEW, true, false, false, false],
            [SegmentStatus::PROCESSED, false, true, false, false],
            [SegmentStatus::CALCULATED, false, false, true, false],
            [SegmentStatus::OUTDATED, false, false, false, true],
        ];
    }

    /**
     * @return array
     */
    public function inValidDataProvider(): array
    {
        return [
            [''],
            ['not exists status'],
            [123],
            ['!@#)(*&(^^*^('],
        ];
    }
}
