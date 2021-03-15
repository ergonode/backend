<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Tests\Domain\ValueObject;

use Ergonode\Channel\Domain\ValueObject\ExportStatus;
use PHPUnit\Framework\TestCase;

class ExportStatusTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testValidCreation(string $status, bool $created, bool $processed, bool $ended, bool $stopped): void
    {
        $vo = new ExportStatus($status);
        $this->assertSame($created, $vo->isCreated());
        $this->assertSame($processed, $vo->isProcessed());
        $this->assertSame($ended, $vo->isEnded());
        $this->assertSame($stopped, $vo->isStopped());
        $this->assertSame($status, $vo->getValue());
    }

    public function testInvalidCreation(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ExportStatus('any invalid status');
    }


    /**
     * @return array|array[]
     */
    public function dataProvider(): array
    {
        return [
            [ExportStatus::CREATED, true, false, false, false],
            [ExportStatus::PRECESSED, false, true, false, false],
            [ExportStatus::ENDED, false, false, true, false],
            [ExportStatus::STOPPED, false, false, false, true],
        ];
    }
}
