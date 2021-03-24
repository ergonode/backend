<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Command\Export;

use Ergonode\Channel\Domain\Command\ExporterCommandInterface;
use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;

class ProductCrossSellingExportCommand implements ExporterCommandInterface
{
    private ExportLineId $lineId;

    private ExportId $exportId;

    private ProductCollectionId $productCollectionId;

    public function __construct(ExportLineId $lineId, ExportId $exportId, ProductCollectionId $productCollectionId)
    {
        $this->lineId = $lineId;
        $this->exportId = $exportId;
        $this->productCollectionId = $productCollectionId;
    }

    public function getLineId(): ExportLineId
    {
        return $this->lineId;
    }

    public function getExportId(): ExportId
    {
        return $this->exportId;
    }

    public function getProductCollectionId(): ProductCollectionId
    {
        return $this->productCollectionId;
    }
}
