<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Factory\Catalog;

use Ergonode\Exporter\Domain\Entity\Catalog\AbstractExportAttributeValue;
use Ergonode\Exporter\Domain\Entity\Catalog\ExportCategoryCode;
use Ergonode\Exporter\Domain\Entity\Catalog\Product\DefaultExportProduct;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 */
class DefaultProductFactory
{
    /**
     * @param Uuid                           $id
     * @param string                         $sku
     * @param Uuid[]                         $categories
     * @param AbstractExportAttributeValue[] $attributes
     *
     * @return DefaultExportProduct
     */
    public function createFromEvent(
        Uuid $id,
        string $sku,
        array $categories = [],
        array $attributes = []
    ): DefaultExportProduct {
        Assert::allIsInstanceOf($categories, Uuid::class);
        Assert::allIsInstanceOf($attributes, AbstractExportAttributeValue::class);

        return new DefaultExportProduct(
            $id,
            $sku,
            $categories,
            $attributes
        );
    }
}
