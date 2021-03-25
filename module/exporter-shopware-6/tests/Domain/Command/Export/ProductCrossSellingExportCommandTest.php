<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Domain\Command\Export;

use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\ExporterShopware6\Domain\Command\Export\ProductCrossSellingExportCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductCrossSellingExportCommandTest extends TestCase
{
    /**
     * @var ExportLineId|MockObject
     */
    private ExportLineId $lineId;

    /**
     * @var ExportId|MockObject
     */
    private ExportId $exportId;

    /**
     * @var ProductCollectionId|MockObject
     */
    private ProductCollectionId $productCollectionId;

    protected function setUp(): void
    {
        $this->lineId = $this->createMock(ExportLineId::class);
        $this->exportId = $this->createMock(ExportId::class);
        $this->productCollectionId = $this->createMock(ProductCollectionId::class);
    }

    public function testCreateCommand(): void
    {
        $command = new ProductCrossSellingExportCommand(
            $this->lineId,
            $this->exportId,
            $this->productCollectionId
        );

        self::assertEquals($this->lineId, $command->getLineId());
        self::assertEquals($this->exportId, $command->getExportId());
        self::assertEquals($this->productCollectionId, $command->getProductCollectionId());
    }
}
