<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Domain\Command\Export;

use Ergonode\ExporterShopware6\Domain\Command\Export\CategoryShopware6ExportCommand;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CategoryShopware6ExportCommandTest extends TestCase
{
    /**
     * @var ExportId|MockObject
     */
    private ExportId $exportId;

    /**
     * @var CategoryId|MockObject
     */
    private CategoryId $categoryId;

    /**
     * @var CategoryId|MockObject
     */
    private CategoryId $parentCategoryId;

    protected function setUp(): void
    {
        $this->exportId = $this->createMock(ExportId::class);
        $this->categoryId = $this->createMock(CategoryId::class);
        $this->parentCategoryId = $this->createMock(CategoryId::class);
    }

    public function testCreateCommand(): void
    {
        $command = new CategoryShopware6ExportCommand(
            $this->exportId,
            $this->categoryId,
            $this->parentCategoryId
        );

        self::assertEquals($this->exportId, $command->getExportId());
        self::assertEquals($this->categoryId, $command->getCategoryId());
        self::assertEquals($this->parentCategoryId, $command->getParentCategoryId());
    }
}
