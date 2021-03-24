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
use Ergonode\ExporterShopware6\Domain\Command\Export\PropertyGroupExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Processor\ExportStepProcessInterface;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Segment\Domain\Query\SegmentProductsQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class PropertyGroupStep implements ExportStepProcessInterface
{
    private ProductQueryInterface $productQuery;

    private SegmentProductsQueryInterface  $segmentProductsQuery;

    private ProductRepositoryInterface $productRepository;

    private CommandBusInterface $commandBus;

    private ExportRepositoryInterface $exportRepository;

    public function __construct(
        ProductQueryInterface $productQuery,
        SegmentProductsQueryInterface $segmentProductsQuery,
        ProductRepositoryInterface $productRepository,
        CommandBusInterface $commandBus,
        ExportRepositoryInterface $exportRepository
    ) {
        $this->productQuery = $productQuery;
        $this->segmentProductsQuery = $segmentProductsQuery;
        $this->productRepository = $productRepository;
        $this->commandBus = $commandBus;
        $this->exportRepository = $exportRepository;
    }

    public function export(ExportId $exportId, Shopware6Channel $channel): void
    {
        $attributeList = array_unique(array_merge($this->getBindingAttributes($channel), $channel->getPropertyGroup()));

        foreach ($attributeList as $attributeId) {
            $lineId = ExportLineId::generate();
            $processCommand = new PropertyGroupExportCommand($lineId, $exportId, $attributeId);
            $this->exportRepository->addLine($lineId, $exportId, $attributeId);
            $this->commandBus->dispatch($processCommand, true);
        }
    }

    /**
     * @return AttributeId[]
     */
    private function getBindingAttributes(Shopware6Channel $channel): array
    {
        $attribute = [];
        $products = $this->getProduct($channel);
        foreach ($products as $product) {
            $productId = new ProductId($product);
            $domainProduct = $this->productRepository->load($productId);
            if (!$domainProduct instanceof  VariableProduct) {
                throw new \LogicException(
                    sprintf(
                        'Expected an instance of %s. %s received.',
                        VariableProduct::class,
                        get_debug_type($domainProduct)
                    )
                );
            }
            $bindings = $domainProduct->getBindings();
            $attribute = array_unique(array_merge($attribute, $bindings));
        }

        return $attribute;
    }

    /**
     * @return array
     */
    private function getProduct(Shopware6Channel $channel): array
    {
        if ($channel->getSegment()) {
            return $this->segmentProductsQuery->getProductsByType($channel->getSegment(), VariableProduct::TYPE);
        }

        return $this->productQuery->findProductIdByType(VariableProduct::TYPE);
    }
}
