<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Model;

use JMS\Serializer\Annotation as JMS;

/**
 */
class CreateShopware6Product extends Shopware6Product
{
    /**
     * @param string|null $sku
     * @param string|null $name
     * @param string|null $description
     * @param bool        $active
     * @param int         $stock
     * @param string|null $taxId
     * @param array|null  $price
     */
    public function __construct(
        ?string $sku = null,
        ?string $name = null,
        ?string $description = null,
        bool $active = false,
        int $stock = 0,
        string $taxId = null,
        array $price = null
    ) {
        parent::__construct(null, $sku, $name, $description);
        $this->active = $active;
        $this->stock = $stock;
        $this->taxId = $taxId;
        $this->price = $price;
    }
}
