<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Domain\Command\Export;

use Ergonode\ExporterFile\Domain\Command\Export\StartFileExportCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

class StartFileExportCommandTest extends TestCase
{
    public function testCreation(): void
    {
        $exportId = $this->createMock(ExportId::class);
        $command = new StartFileExportCommand($exportId);
        self::assertSame($exportId, $command->getExportId());
    }
}
