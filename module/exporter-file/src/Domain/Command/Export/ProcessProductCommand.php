<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Domain\Command\Export;

use Ergonode\Channel\Domain\Command\ExporterCommandInterface;
use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class ProcessProductCommand implements ExporterCommandInterface
{
    /**
     * @JMS\Type("Ergonode\Channel\Domain\ValueObject\ExportLineId")
     */
    private ExportLineId $lineId;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportId")
     */
    private ExportId $exportId;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $productId;

    public function __construct(ExportLineId $lineId, ExportId $exportId, ProductId $productId)
    {
        $this->lineId = $lineId;
        $this->exportId = $exportId;
        $this->productId = $productId;
    }

    public function getLineId(): ExportLineId
    {
        return $this->lineId;
    }

    public function getExportId(): ExportId
    {
        return $this->exportId;
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }
}
