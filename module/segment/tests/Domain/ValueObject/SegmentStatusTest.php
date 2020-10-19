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
    public function testValidCreation(
        string $string,
        bool $new,
        bool $processed,
        bool $calculated,
        bool $outdated
    ): void {
        $status = new SegmentStatus($string);
        self::assertEquals(strtoupper($string), (string) $string);
        self::assertTrue(SegmentStatus::isValid($string));
        self::assertEquals($new, $status->isNew());
        self::assertEquals($processed, $status->isProcessed());
        self::assertEquals($calculated, $status->isCalculated());
        self::assertEquals($outdated, $status->isOutdated());
    }

    /**
     * @param string $status
     *
     * @dataProvider validDataProvider
     */
    public function testPositiveValidation(string $status): void
    {
        self::assertTrue(SegmentStatus::isValid($status));
    }

    /**
     * @param string $status
     *
     * @dataProvider inValidDataProvider
     */
    public function testNegativeValidation(string $status): void
    {
        self::assertFalse(SegmentStatus::isValid($status));
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
        new SegmentStatus($status);
    }

    /**
     * @param string $status
     *
     * @dataProvider inValidDataProvider
     *
     */
    public function testEquality(string $status): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $status1 = new SegmentStatus(SegmentStatus::CALCULATED);
        $status2 = new SegmentStatus(SegmentStatus::CALCULATED);
        $status3 = new SegmentStatus(SegmentStatus::OUTDATED);

        self::assertTrue($status1->isEqual($status2));
        self::assertTrue($status2->isEqual($status1));
        self::assertFalse($status1->isEqual($status3));
        self::assertFalse($status2->isEqual($status3));
        self::assertFalse($status3->isEqual($status1));
        self::assertFalse($status3->isEqual($status1));

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
            ['123'],
            ['!@#)(*&(^^*^('],
        ];
    }
}
