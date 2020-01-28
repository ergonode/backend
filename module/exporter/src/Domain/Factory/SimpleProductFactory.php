<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Factory;

use Ergonode\Exporter\Domain\Entity\AbstractExportAttributeValue;
use Ergonode\Exporter\Domain\Entity\ExportCategoryCode;
use Ergonode\Exporter\Domain\Entity\Product\SimpleExportProduct;
use Webmozart\Assert\Assert;

/**
 */
class SimpleProductFactory
{
    /**
     * @param string                         $id
     * @param string                         $sku
     * @param ExportCategoryCode[]           $categories
     * @param AbstractExportAttributeValue[] $attributes
     *
     * @return SimpleExportProduct
     */
    public function createFromEvent(
        string $id,
        string $sku,
        array $categories = [],
        array $attributes = []
    ): SimpleExportProduct {
        Assert::allIsInstanceOf($categories, ExportCategoryCode::class);
        Assert::allIsInstanceOf($attributes, AbstractExportAttributeValue::class);

        return new SimpleExportProduct(
            $id,
            $sku,
            $categories,
            $attributes
        );
    }
}
