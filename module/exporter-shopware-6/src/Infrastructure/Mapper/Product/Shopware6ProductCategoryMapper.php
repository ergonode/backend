<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CategoryRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductCategory;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;

class Shopware6ProductCategoryMapper implements Shopware6ProductMapperInterface
{
    private Shopware6CategoryRepositoryInterface $categoryRepository;

    public function __construct(Shopware6CategoryRepositoryInterface $categoryRepository)
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
