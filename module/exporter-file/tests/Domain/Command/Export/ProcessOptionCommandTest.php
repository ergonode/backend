<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Domain\Command\Export;

use Ergonode\ExporterFile\Domain\Command\Export\ProcessOptionCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\AggregateId;

class ProcessOptionCommandTest extends TestCase
{
    public function testCreation(): void
    {
        $exportId = $this->createMock(ExportId::class);
        $optionId = $this->createMock(AggregateId::class);
        $command = new ProcessOptionCommand($exportId, $optionId);
        self::assertSame($exportId, $command->getExportId());
        self::assertSame($optionId, $command->getOptionId());
    }
}
