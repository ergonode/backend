<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

/**
 */
class CategoryIdFaker extends BaseProvider
{
    private const NAMESPACE = '4438d266-ec62-473b-9f46-1a767e2060d4';

    /**
     * @param string|null $code
     *
     * @return CategoryId
     *
     * @throws \Exception
     */
    public function categoryId(?string $code = null): CategoryId
    {
        if ($code) {
            return new CategoryId(Uuid::uuid5(self::NAMESPACE, $code)->toString());
        }

        return CategoryId::generate();
    }
}
