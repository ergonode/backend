<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Step;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\ProductShopware6ExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Shopware6ExportStepProcessInterface;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Segment\Domain\Query\SegmentProductsQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 */
class Shopware6SimpleProductStep implements Shopware6ExportStepProcessInterface
{
    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $productQuery;

    /**
     * @var SegmentProductsQueryInterface
     */
    private SegmentProductsQueryInterface  $segmentProductsQuery;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param ProductQueryInterface         $productQuery
     * @param SegmentProductsQueryInterface $segmentProductsQuery
     * @param CommandBusInterface           $commandBus
     */
    public function __construct(
        ProductQueryInterface $productQuery,
        SegmentProductsQueryInterface $segmentProductsQuery,
        CommandBusInterface $commandBus
    ) {
        $this->productQuery = $productQuery;
        $this->segmentProductsQuery = $segmentProductsQuery;
        $this->commandBus = $commandBus;
    }

    /**
     * @param ExportId         $exportId
     * @param Shopware6Channel $channel
     */
    public function export(ExportId $exportId, Shopware6Channel $channel): void
    {
        $productList = $this->getProduct($channel);
        foreach ($productList as $product) {
            $productId = new ProductId($product);

            $processCommand = new ProductShopware6ExportCommand($exportId, $productId);
            $this->commandBus->dispatch($processCommand, true);
        }
    }


    /**
     * @param Shopware6Channel $channel
     *
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
