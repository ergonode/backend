<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Product;

use Ergonode\ExporterShopware6\Domain\Entity\Shopware6ExportApiProfile;
use Ergonode\ExporterShopware6\Domain\Repository\Shopwer6CategoryRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6ProductMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Product;
use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
class Shopware6ProductCategoryMapper implements Shopware6ProductMapperInterface
{
    /**
     * @var Shopwer6CategoryRepositoryInterface
     */
    private Shopwer6CategoryRepositoryInterface $categoryRepository;

    /**
     * @param Shopwer6CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(Shopwer6CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param Shopware6Product          $shopware6Product
     * @param AbstractProduct           $product
     * @param Shopware6ExportApiProfile $profile
     *
     * @return Shopware6Product
     */
    public function map(
        Shopware6Product $shopware6Product,
        AbstractProduct $product,
        Shopware6ExportApiProfile $profile
    ): Shopware6Product {
        $categoryList = $product->getCategories();
        foreach ($categoryList as $categoryId) {
            $shopwareCategory = $this->categoryRepository->load($profile->getId(), $categoryId);
            if ($shopwareCategory) {
                $shopware6Product->addCategoryId($shopwareCategory->getId());
            }
        }

        return $shopware6Product;
    }
}
