<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Faker\Provider\Base as BaseProvider;
use Ergonode\Product\Domain\Entity\ProductId;

/**
 */
class ProductIdFaker extends BaseProvider
{
    /**
     * @param string|null $name
     *
     * @return ProductId
     *
     * @throws \Exception
     */
    public function productId(?string $name = null): ProductId
    {
        if ($name) {
            return productId::fromString($name);
        }

        return ProductId::generate();
    }
}
