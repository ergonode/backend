<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Domain\Command\Export;

use Ergonode\ExporterShopware6\Domain\Command\Export\CategoryRemoveShopware6ExportCommand;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CategoryRemoveShopware6ExportCommandTest extends TestCase
{
    /**
     * @var ExportId|MockObject
     */
    private ExportId $exportId;

    /**
     * @var CategoryId|MockObject
     */
    private CategoryId $categoryId;

    protected function setUp(): void
    {
        $this->exportId = $this->createMock(ExportId::class);
        $this->categoryId = $this->createMock(CategoryId::class);
    }

    public function testCreateCommand(): void
    {
        $command = new CategoryRemoveShopware6ExportCommand(
            $this->exportId,
            $this->categoryId
        );

        self::assertEquals($this->exportId, $command->getExportId());
        self::assertEquals($this->categoryId, $command->getCategoryId());
    }
}
