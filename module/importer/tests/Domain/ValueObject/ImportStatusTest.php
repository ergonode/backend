<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Tests\Domain\ValueObject;

use Ergonode\Importer\Domain\ValueObject\ImportStatus;
use PHPUnit\Framework\TestCase;

/**
 * Class ImportStatusTest
 */
class ImportStatusTest extends TestCase
{
    /**
     * @dataProvider importStatusProvider
     */
    public function testCreateWitchCorrectStatus(string $status): void
    {
        $status = new ImportStatus($status);

        $this->assertEquals($status, (string) $status);
    }

    public function testCreateWitchIncorrectStatus(): void
    {
        $this->expectException(\InvalidArgumentException::class);
         new ImportStatus('any incorrect status');
    }

    public static function importStatusProvider(): \Generator
    {
        foreach (ImportStatus::AVAILABLE as $status) {
            yield [
                $status,
            ];
        }
    }
}
