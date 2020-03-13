<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

/**
 */
class CategoryTreeIdFaker extends BaseProvider
{
    private const NAMESPACE = 'f39d019e-92f0-47e8-b5ee-81155e7ddfc2';

    /**
     * @param string|null $code
     *
     * @return CategoryTreeId
     *
     * @throws \Exception
     */
    public function categoryTreeId(?string $code = null): CategoryTreeId
    {

        if ($code) {
            return new CategoryTreeId(Uuid::uuid5(self::NAMESPACE, $code)->toString());
        }

        return CategoryTreeId::generate();
    }
}
