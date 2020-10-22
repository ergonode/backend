<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Domain\Command\Export;

use Ergonode\ExporterShopware6\Domain\Command\Export\ProductShopware6ExportCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductShopware6ExportCommandTest extends TestCase
{
    /**
     * @var ExportId|MockObject
     */
    private ExportId $exportId;

    /**
     * @var ProductId|MockObject
     */
    private ProductId $productId;

    protected function setUp(): void
    {
        $this->exportId = $this->createMock(ExportId::class);
        $this->productId = $this->createMock(ProductId::class);
    }

    public function testCreateCommand(): void
    {
        $command = new ProductShopware6ExportCommand(
            $this->exportId,
            $this->productId
        );

        self::assertEquals($this->exportId, $command->getExportId());
        self::assertEquals($this->productId, $command->getProductId());
    }
}
