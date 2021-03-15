<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductCategory;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;

class ProductCategoryMapper implements ProductMapperInterface
{
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function map(
        Shopware6Channel $channel,
        Export $export,
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        ?Language $language = null
    ): Shopware6Product {
        $categoryList = $product->getCategories();
        foreach ($categoryList as $categoryId) {
            $shopwareCategoryId = $this->categoryRepository->load($channel->getId(), $categoryId);
            if ($shopwareCategoryId) {
                $shopware6Product->addCategory(new Shopware6ProductCategory($shopwareCategoryId));
            }
        }

        return $shopware6Product;
    }
}
