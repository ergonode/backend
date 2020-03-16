<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

/**
 */
class ProductIdFaker extends BaseProvider
{
    public const NAMESPACE = '7cf84041-304b-41c9-8401-139d9203735e';

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
            return new ProductId(Uuid::uuid5(self::NAMESPACE, $name)->toString());
        }

        return ProductId::generate();
    }
}
