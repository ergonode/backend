<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Tests\Domain\Command\Export;

use Ergonode\Channel\Domain\Command\Export\ProcessExportCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

class ProcessExportCommandTest extends TestCase
{
    public function testCommandCreation(): void
    {
        $exportId = $this->createMock(ExportId::class);

        $command = new ProcessExportCommand($exportId);
        $this->assertSame($exportId, $command->getExportId());
    }
}
