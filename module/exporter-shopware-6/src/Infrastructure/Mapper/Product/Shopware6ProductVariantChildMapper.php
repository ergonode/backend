<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6ProductRepositoryInterface;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6PropertyGroupOptionsRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Calculator\AttributeTranslationInheritanceCalculator;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Domain\Query\ProductChildrenQueryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class Shopware6ProductVariantChildMapper extends AbstractShopware6VariantOptionMapper
{
    private ProductChildrenQueryInterface  $childQuery;

    private Shopware6ProductRepositoryInterface $shopware6ProductRepository;

    private ProductRepositoryInterface $productRepository;

    public function __construct(
        ProductChildrenQueryInterface $childQuery,
        Shopware6ProductRepositoryInterface $shopware6ProductRepository,
        ProductRepositoryInterface $productRepository,
        AttributeRepositoryInterface $attributeRepository,
        OptionRepositoryInterface $optionRepository,
        AttributeTranslationInheritanceCalculator $calculator,
        Shopware6PropertyGroupOptionsRepositoryInterface $propertyGroupOptionsRepository
    ) {
        parent::__construct($attributeRepository, $optionRepository, $calculator, $propertyGroupOptionsRepository);
        $this->childQuery = $childQuery;
        $this->shopware6ProductRepository = $shopware6ProductRepository;
        $this->productRepository = $productRepository;
    }

    public function map(
        Shopware6Channel $channel,
        Export $export,
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        ?Language $language = null
    ): Shopware6Product {
        $parentsIds = $this->childQuery->findProductIdByProductChildrenId($product->getId());
        if (!empty($parentsIds)) {
            $parentProductId = reset($parentsIds);
            $this->parentMap($shopware6Product, $parentProductId, $product, $channel);
        }

        return $shopware6Product;
    }

    private function parentMap(
        Shopware6Product $shopware6Product,
        ProductId $parentProductId,
        AbstractProduct $product,
        Shopware6Channel $channel
    ): Shopware6Product {

        $parentProduct = $this->productRepository->load($parentProductId);
        if ($parentProduct instanceof VariableProduct) {
            $parent = $this->shopware6ProductRepository->load($channel->getId(), $parentProductId);
            $shopware6Product->setParentId($parent);
            foreach ($parentProduct->getBindings() as $bindingId) {
                $shopwareOption = $this->optionMapper($bindingId, $product, $channel);
                if ($shopwareOption) {
                    $shopware6Product->addOptions($shopwareOption);
                }
            }
        }

        return $shopware6Product;
    }
}
