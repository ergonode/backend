<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Domain\Command\Export;

use Ergonode\ExporterFile\Domain\Command\Export\ProcessCategoryCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

class ProcessCategoryCommandTest extends TestCase
{
    public function testCreation(): void
    {
        $exportId = $this->createMock(ExportId::class);
        $categoryId = $this->createMock(CategoryId::class);
        $command = new ProcessCategoryCommand($exportId, $categoryId);
        self::assertSame($exportId, $command->getExportId());
        self::assertSame($categoryId, $command->getCategoryId());
    }
}
