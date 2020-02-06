<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Provider;

use Ergonode\Exporter\Domain\Entity\Catalog\Product\DefaultExportProduct;
use Ergonode\Exporter\Domain\Factory\Catalog\AttributeFactory;
use Ergonode\Exporter\Domain\Factory\Catalog\CategoryCodeFactory;
use Ergonode\Exporter\Domain\Factory\Catalog\DefaultProductFactory;
use Ramsey\Uuid\Uuid;

/**
 */
class ProductProvider
{
    /**
     * @var DefaultProductFactory
     */
    private DefaultProductFactory $productFactory;

    /**
     * @var CategoryCodeFactory
     */
    private CategoryCodeFactory $categoryCodeFactory;

    /**
     * @var AttributeFactory
     */
    private AttributeFactory $attributeFactory;

    /**
     * ProductProvider constructor.
     * @param DefaultProductFactory $productFactory
     * @param CategoryCodeFactory   $categoryCodeFactory
     * @param AttributeFactory      $attributeFactory
     */
    public function __construct(
        DefaultProductFactory $productFactory,
        CategoryCodeFactory $categoryCodeFactory,
        AttributeFactory $attributeFactory
    ) {
        $this->productFactory = $productFactory;
        $this->categoryCodeFactory = $categoryCodeFactory;
        $this->attributeFactory = $attributeFactory;
    }

    /**
     * @param Uuid   $id
     * @param string $sku
     * @param array  $categories
     * @param array  $attributes
     *
     * @return DefaultExportProduct
     */
    public function createFromEvent(
        Uuid $id,
        string $sku,
        array $categories = [],
        array $attributes = []
    ): DefaultExportProduct {
        $categories = $this->categoryCodeFactory->createList($categories);
        $attributes = $this->attributeFactory->createList($attributes);

        return $this->productFactory->createFromEvent($id, $sku, $categories, $attributes);
    }
}
