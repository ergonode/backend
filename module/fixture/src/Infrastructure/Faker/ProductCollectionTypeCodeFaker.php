<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use Faker\Provider\Base as BaseProvider;

class ProductCollectionTypeCodeFaker extends BaseProvider
{
    /**
     * @throws \Exception
     */
    public function productCollectionTypeCode(?string $code = null): ProductCollectionTypeCode
    {
        if ($code) {
            return new ProductCollectionTypeCode($code);
        }

        return new ProductCollectionTypeCode(sprintf('code_%s_%s', random_int(1, 1000000), random_int(1, 1000000)));
    }
}
