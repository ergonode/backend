<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Faker\Provider\Base as BaseProvider;

class AttributeCodeFaker extends BaseProvider
{
    /**
     * @throws \Exception
     */
    public function attributeCode(?string $code = null): AttributeCode
    {
        if ($code) {
            return new AttributeCode($code);
        }

        return new AttributeCode(sprintf('code_%s_%s', random_int(1, 1000000), random_int(1, 1000000)));
    }
}
