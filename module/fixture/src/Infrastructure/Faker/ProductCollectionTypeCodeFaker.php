<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use Faker\Provider\Base as BaseProvider;

/**
 */
class ProductCollectionTypeCodeFaker extends BaseProvider
{
    /**
     * @param string|null $code
     *
     * @return ProductCollectionTypeCode
     *
     * @throws \Exception
     */
    public function productCollectionTypeCode(?string $code = null): ProductCollectionTypeCode
    {
//        if ($code) {
//            return new ProductCollectionTypeCode($code);
//        }

        return new ProductCollectionTypeCode('dupa');
    }
}
