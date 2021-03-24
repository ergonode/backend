<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Domain\Command\Export;

use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\ExporterShopware6\Domain\Command\Export\ProductExportCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductExportCommandTest extends TestCase
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
     * @var ProductId|MockObject
     */
    private ProductId $productId;

    protected function setUp(): void
    {
        $this->lineId = $this->createMock(ExportLineId::class);
        $this->exportId = $this->createMock(ExportId::class);
        $this->productId = $this->createMock(ProductId::class);
    }

    public function testCreateCommand(): void
    {
        $command = new ProductExportCommand(
            $this->lineId,
            $this->exportId,
            $this->productId
        );

        self::assertEquals($this->lineId, $command->getLineId());
        self::assertEquals($this->exportId, $command->getExportId());
        self::assertEquals($this->productId, $command->getProductId());
    }
}
