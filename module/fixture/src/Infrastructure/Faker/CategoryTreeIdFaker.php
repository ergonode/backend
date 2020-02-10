<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Faker\Provider\Base as BaseProvider;

/**
 */
class CategoryTreeIdFaker extends BaseProvider
{
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
            return CategoryTreeId::fromKey($code);
        }

        return CategoryTreeId::generate();
    }
}
