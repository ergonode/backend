<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Factory;

use Ergonode\Exporter\Domain\Entity\Product\SimpleProduct;

/**
 */
class SimpleProductFactory
{
    /**
     * @param string $id
     * @param string $sku
     * @param array  $categories
     * @param array  $attributes
     *
     * @return SimpleProduct
     */
    public static function createFromEvent(
        string $id,
        string $sku,
        array $categories = [],
        array $attributes = []
    ): SimpleProduct {

        return new SimpleProduct(
            $id,
            $sku,
            CategoryCodeFactory::createList($categories),
            AttributeFactory::createList($attributes)
        );
    }
}
