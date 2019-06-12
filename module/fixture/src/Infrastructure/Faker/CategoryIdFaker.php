<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Faker\Provider\Base as BaseProvider;

/**
 */
class CategoryIdFaker extends BaseProvider
{
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
            return CategoryId::fromCode(new CategoryCode($code));
        }

        return CategoryId::generate();
    }
}
