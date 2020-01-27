<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Factory;

use Ergonode\Exporter\Domain\Entity\AbstractAttribute;
use Ergonode\Exporter\Domain\Entity\CategoryCode;
use Ergonode\Exporter\Domain\Entity\Product\SimpleProduct;
use Webmozart\Assert\Assert;

/**
 */
class SimpleProductFactory
{
    /**
     * @param string              $id
     * @param string              $sku
     * @param CategoryCode[]      $categories
     * @param AbstractAttribute[] $attributes
     *
     * @return SimpleProduct
     */
    public function createFromEvent(
        string $id,
        string $sku,
        array $categories = [],
        array $attributes = []
    ): SimpleProduct {
        Assert::allIsInstanceOf($categories, CategoryCode::class);
        Assert::allIsInstanceOf($attributes, AbstractAttribute::class);

        return new SimpleProduct(
            $id,
            $sku,
            $categories,
            $attributes
        );
    }
}
