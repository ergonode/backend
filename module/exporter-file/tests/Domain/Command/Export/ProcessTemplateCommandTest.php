<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Domain\Command\Export;

use Ergonode\ExporterFile\Domain\Command\Export\ProcessTemplateCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class ProcessTemplateCommandTest extends TestCase
{
    public function testCreation(): void
    {
        $exportId = $this->createMock(ExportId::class);
        $templateId = $this->createMock(TemplateId::class);
        $command = new ProcessTemplateCommand($exportId, $templateId);
        self::assertSame($exportId, $command->getExportId());
        self::assertSame($templateId, $command->getTemplateId());
    }
}
