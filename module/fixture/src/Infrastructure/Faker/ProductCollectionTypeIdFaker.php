<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

/**
 */
class ProductCollectionTypeIdFaker extends BaseProvider
{
    public const NAMESPACE = '5205c16c-5534-4aef-811a-f6bb5ef0dca2';

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
            return new ProductCollectionTypeId(Uuid::uuid5(self::NAMESPACE, $string)->toString());
        }

        return ProductCollectionTypeId::generate();
    }
}
