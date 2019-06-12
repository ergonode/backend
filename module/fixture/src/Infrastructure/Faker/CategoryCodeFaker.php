<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Faker\Provider\Base as BaseProvider;

/**
 */
class CategoryCodeFaker extends BaseProvider
{
    /**
     * @param string|null $code
     *
     * @return CategoryCode
     *
     * @throws \Exception
     */
    public function categoryCode(?string $code = null): CategoryCode
    {
        if ($code) {
            return new CategoryCode($code);
        }

        return new CategoryCode(sprintf('code_%s_%s', random_int(1, 1000000), random_int(1, 1000000)));
    }
}
