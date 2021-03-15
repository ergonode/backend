<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Domain\Command\Export;

use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessAttributeCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class ProcessAttributeCommandTest extends TestCase
{
    public function testCreation(): void
    {
        $lineId = $this->createMock(ExportLineId::class);
        $exportId = $this->createMock(ExportId::class);
        $attributeId = $this->createMock(AttributeId::class);
        $command = new ProcessAttributeCommand($lineId, $exportId, $attributeId);
        self::assertSame($lineId, $command->getLineId());
        self::assertSame($exportId, $command->getExportId());
        self::assertSame($attributeId, $command->getAttributeId());
    }
}
