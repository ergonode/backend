<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Product\Domain\ValueObject\Sku;

/**
 */
class SkuFaker
{
    /**
     * @param string|null $sku
     *
     * @return Sku
     *
     * @throws \Exception
     */
    public function sku(?string $sku = null): Sku
    {
        if ($sku) {
            return new Sku($sku);
        }

        return new Sku(sprintf('SKU_%s_%s', time(), random_int(0, time())));
    }
}
