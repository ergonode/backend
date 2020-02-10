<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\ProductCollection\Domain\Entity\ProductCollectionTypeId;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use Faker\Provider\Base as BaseProvider;

/**
 */
class ProductCollectionTypeIdFaker extends BaseProvider
{
    /**
     * @param string|null $string
     *
     * @return ProductCollectionTypeId
     *
     * @throws \Exception
     */
    public function productCollectionTypeId(?string $string = null): ProductCollectionTypeId
    {

        if ($string) {
            return ProductCollectionTypeId::fromCode(new ProductCollectionTypeCode($string));
        }

        return ProductCollectionTypeId::generate();
    }
}
