<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Step;

use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\ProductExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Processor\ExportStepProcessInterface;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Segment\Domain\Query\SegmentProductsQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class SimpleProductStep implements ExportStepProcessInterface
{
    private ProductQueryInterface $productQuery;

    private SegmentProductsQueryInterface  $segmentProductsQuery;

    private CommandBusInterface $commandBus;

    private ExportRepositoryInterface $exportRepository;

    public function __construct(
        ProductQueryInterface $productQuery,
        SegmentProductsQueryInterface $segmentProductsQuery,
        CommandBusInterface $commandBus,
        ExportRepositoryInterface $exportRepository
    ) {
        $this->productQuery = $productQuery;
        $this->segmentProductsQuery = $segmentProductsQuery;
        $this->commandBus = $commandBus;
        $this->exportRepository = $exportRepository;
    }


    public function export(ExportId $exportId, Shopware6Channel $channel): void
    {
        $productList = $this->getProduct($channel);
        foreach ($productList as $product) {
            $productId = new ProductId($product);
            $lineId = ExportLineId::generate();

            $processCommand = new ProductExportCommand($lineId, $exportId, $productId);
            $this->exportRepository->addLine($lineId, $exportId, $productId);
            $this->commandBus->dispatch($processCommand, true);
        }
    }


    /**
     * @return array
     */
    private function getProduct(Shopware6Channel $channel): array
    {
        if ($channel->getSegment()) {
            return $this->segmentProductsQuery->getProductsByType($channel->getSegment(), SimpleProduct::TYPE);
        }

        return $this->productQuery->findProductIdByType(SimpleProduct::TYPE);
    }
}
