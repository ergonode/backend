<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
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

/**
 */
class Shopware6ProductVariantChildMapper extends AbstractShopware6VariantOptionMapper
{
    /**
     * @var ProductChildrenQueryInterface
     */
    private ProductChildrenQueryInterface  $childQuery;

    /**
     * @var Shopware6ProductRepositoryInterface
     */
    private Shopware6ProductRepositoryInterface $shopware6ProductRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @param ProductChildrenQueryInterface                    $childQuery
     * @param Shopware6ProductRepositoryInterface              $shopware6ProductRepository
     * @param ProductRepositoryInterface                       $productRepository
     * @param AttributeRepositoryInterface                     $attributeRepository
     * @param OptionRepositoryInterface                        $optionRepository
     * @param AttributeTranslationInheritanceCalculator        $calculator
     * @param Shopware6PropertyGroupOptionsRepositoryInterface $propertyGroupOptionsRepository
     */
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

    /**
     * @param Shopware6Product $shopware6Product
     * @param AbstractProduct  $product
     * @param Shopware6Channel $channel
     * @param Language|null    $language
     *
     * @return Shopware6Product
     */
    public function map(
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        Shopware6Channel $channel,
        ?Language $language = null
    ): Shopware6Product {
        $parentsIds = $this->childQuery->findProductIdByProductChildrenId($product->getId());
        if (!empty($parentsIds)) {
            $parentProductId = reset($parentsIds);
            $this->parentMap($shopware6Product, $parentProductId, $product, $channel);
        }

        return $shopware6Product;
    }

    /**
     * @param Shopware6Product $shopware6Product
     * @param ProductId        $parentProductId
     * @param AbstractProduct  $product
     * @param Shopware6Channel $channel
     *
     * @return Shopware6Product
     */
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
