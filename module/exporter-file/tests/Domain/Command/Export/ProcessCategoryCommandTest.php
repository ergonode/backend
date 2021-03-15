<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Domain\Command\Export;

use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessCategoryCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

class ProcessCategoryCommandTest extends TestCase
{
    public function testCreation(): void
    {
        $lineId = $this->createMock(ExportLineId::class);
        $exportId = $this->createMock(ExportId::class);
        $categoryId = $this->createMock(CategoryId::class);
        $command = new ProcessCategoryCommand($lineId, $exportId, $categoryId);
        self::assertSame($lineId, $command->getLineId());
        self::assertSame($exportId, $command->getExportId());
        self::assertSame($categoryId, $command->getCategoryId());
    }
}
