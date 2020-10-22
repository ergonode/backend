<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Tests\Domain\Command\Export;

use Ergonode\ExporterFile\Domain\Command\Export\ProcessAttributeCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class ProcessAttributeCommandTest extends TestCase
{
    public function testCreation(): void
    {
        $exportId = $this->createMock(ExportId::class);
        $attributeId = $this->createMock(AttributeId::class);
        $command = new ProcessAttributeCommand($exportId, $attributeId);
        self::assertSame($exportId, $command->getExportId());
        self::assertSame($attributeId, $command->getAttributeId());
    }
}
