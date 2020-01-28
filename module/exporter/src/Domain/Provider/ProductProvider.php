<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Provider;

use Ergonode\Exporter\Domain\Entity\Product\SimpleExportProduct;
use Ergonode\Exporter\Domain\Factory\AttributeFactory;
use Ergonode\Exporter\Domain\Factory\CategoryCodeFactory;
use Ergonode\Exporter\Domain\Factory\SimpleProductFactory;

/**
 */
class ProductProvider
{
    /**
     * @var SimpleProductFactory
     */
    private SimpleProductFactory $productFactory;

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
     * @param SimpleProductFactory $productFactory
     * @param CategoryCodeFactory  $categoryCodeFactory
     * @param AttributeFactory     $attributeFactory
     */
    public function __construct(
        SimpleProductFactory $productFactory,
        CategoryCodeFactory $categoryCodeFactory,
        AttributeFactory $attributeFactory
    ) {
        $this->productFactory = $productFactory;
        $this->categoryCodeFactory = $categoryCodeFactory;
        $this->attributeFactory = $attributeFactory;
    }

    /**
     * @param string $id
     * @param string $sku
     * @param array  $categories
     * @param array  $attributes
     *
     * @return SimpleExportProduct
     */
    public function createFromEvent(
        string $id,
        string $sku,
        array $categories = [],
        array $attributes = []
    ): SimpleExportProduct {
        $categories = $this->categoryCodeFactory->createList($categories);
        $attributes = $this->attributeFactory->createList($attributes);

        return $this->productFactory->createFromEvent($id, $sku, $categories, $attributes);
    }
}
