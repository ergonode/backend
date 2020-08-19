<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Command\Export;

use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 */
class ProcessShopware6ExportCommand implements DomainCommandInterface
{
    /**
     * @var ExportId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportId")
     */
    private ExportId $exportId;

    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $productId;

    /**
     * @param ExportId  $exportId
     * @param ProductId $productId
     */
    public function __construct(ExportId $exportId, ProductId $productId)
    {
        $this->exportId = $exportId;
        $this->productId = $productId;
    }

    /**
     * @return ExportId
     */
    public function getExportId(): ExportId
    {
        return $this->exportId;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }
}
