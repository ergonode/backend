<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use Faker\Provider\Base as BaseProvider;

class ProductCollectionCodeFaker extends BaseProvider
{
    /**
     * @throws \Exception
     */
    public function productCollectionCode(?string $code = null): ProductCollectionCode
    {
        if ($code) {
            return new ProductCollectionCode($code);
        }

        return new ProductCollectionCode(sprintf('code_%s_%s', random_int(1, 1000000), random_int(1, 1000000)));
    }
}
