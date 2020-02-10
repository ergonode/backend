<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionCode;
use Faker\Provider\Base as BaseProvider;

/**
 */
class ProductCollectionIdFaker extends BaseProvider
{
    /**
     * @param string|null $code
     *
     * @return ProductCollectionId
     *
     * @throws \Exception
     */
    public function productCollectionId(?string $code = null): ProductCollectionId
    {

        if ($code) {
            return ProductCollectionId::fromCode((new ProductCollectionCode($code))->getValue());
        }

        return ProductCollectionId::generate();
    }
}
