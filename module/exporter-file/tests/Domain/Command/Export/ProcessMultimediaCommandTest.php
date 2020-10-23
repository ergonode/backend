<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Domain\Command\Export;

use Ergonode\ExporterFile\Domain\Command\Export\ProcessMultimediaCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

class ProcessMultimediaCommandTest extends TestCase
{
    public function testCreation(): void
    {
        $exportId = $this->createMock(ExportId::class);
        $multimediaId = $this->createMock(MultimediaId::class);
        $command = new ProcessMultimediaCommand($exportId, $multimediaId);
        self::assertSame($exportId, $command->getExportId());
        self::assertSame($multimediaId, $command->getMultimediaId());
    }
}
