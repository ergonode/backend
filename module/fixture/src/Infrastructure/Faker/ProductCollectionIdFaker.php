<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

/**
 */
class ProductCollectionIdFaker extends BaseProvider
{
    private const NAMESPACE = 'a6edc906-2f9f-5fb2-a373-efac406f0ef2';

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
            return new ProductCollectionId(Uuid::uuid5(self::NAMESPACE, $code)->toString());
        }

        return ProductCollectionId::generate();
    }
}
