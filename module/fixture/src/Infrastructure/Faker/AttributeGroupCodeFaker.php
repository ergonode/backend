<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode;
use Faker\Provider\Base as BaseProvider;

class AttributeGroupCodeFaker extends BaseProvider
{
    /**
     * @throws \Exception
     */
    public function attributeGroupCode(?string $code = null): AttributeGroupCode
    {
        if ($code) {
            return new AttributeGroupCode($code);
        }

        return new AttributeGroupCode(sprintf('code_%s_%s', random_int(1, 1000000), random_int(1, 1000000)));
    }
}
