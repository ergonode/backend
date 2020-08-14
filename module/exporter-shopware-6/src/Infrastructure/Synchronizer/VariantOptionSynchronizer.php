<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Synchronizer;

use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6PropertyGroupClient;
use Ergonode\ExporterShopware6\Infrastructure\Client\Shopware6PropertyGroupOptionClient;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Webmozart\Assert\Assert;

/**
 */
class VariantOptionSynchronizer extends AbstractPropertyOptionSynchronizer
{
    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $productQuery;
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @param ProductQueryInterface                     $productQuery
     * @param ProductRepositoryInterface                $productRepository
     * @param AttributeRepositoryInterface              $attributeRepository
     * @param Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository
     * @param Shopware6PropertyGroupClient              $propertyGroupClient
     * @param OptionQueryInterface                      $optionQuery
     * @param OptionRepositoryInterface                 $optionRepository
     * @param Shopware6PropertyGroupOptionClient        $propertyGroupOptionClient
     */
    public function __construct(
        ProductQueryInterface $productQuery,
        ProductRepositoryInterface $productRepository,
        AttributeRepositoryInterface $attributeRepository,
        Shopware6PropertyGroupRepositoryInterface $propertyGroupRepository,
        Shopware6PropertyGroupClient $propertyGroupClient,
        OptionQueryInterface $optionQuery,
        OptionRepositoryInterface $optionRepository,
        Shopware6PropertyGroupOptionClient $propertyGroupOptionClient
    ) {
        parent::__construct(
            $attributeRepository,
            $propertyGroupRepository,
            $propertyGroupClient,
            $optionQuery,
            $optionRepository,
            $propertyGroupOptionClient
        );
        $this->productQuery = $productQuery;
        $this->productRepository = $productRepository;
    }

    /**
     * @param ExportId         $id
     * @param Shopware6Channel $channel
     */
    public function synchronize(ExportId $id, Shopware6Channel $channel): void
    {
        foreach ($this->productQuery->findProductIdByType(VariableProduct::TYPE) as $product) {
            $productId = new ProductId($product);
            $product = $this->productRepository->load($productId);
            if ($product instanceof VariableProduct) {
                $this->synchronizeProductOptions($product, $channel);
            }
        }
    }

    /**
     * @param VariableProduct  $product
     * @param Shopware6Channel $channel
     */
    private function synchronizeProductOptions(
        VariableProduct $product,
        Shopware6Channel $channel
    ): void {
        foreach ($product->getBindings() as $bindingId) {
            $attribute = $this->attributeRepository->load($bindingId);
            Assert::notNull($attribute);
            $this->checkOrCreateWithOptions($channel, $attribute);
        }
    }
}
