<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Tests\Domain\Command\Export;

use Ergonode\Exporter\Domain\Command\Export\StartExportCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
class StartExportCommandTest extends TestCase
{
    /**
     */
    public function testCommandCreation(): void
    {
        $exportId = $this->createMock(ExportId::class);

        $command = new StartExportCommand($exportId);
        $this->assertSame($exportId, $command->getExportId());
    }
}
