<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Faker\Provider\Base as BaseProvider;

/**
 */
class AttributeIdFaker extends BaseProvider
{
    /**
     * @param string|null $code
     *
     * @return AttributeId
     *
     * @throws \Exception
     */
    public function attributeId(?string $code = null): AttributeId
    {

        if ($code) {
            return AttributeId::fromKey((new AttributeCode($code))->getValue());
        }

        return AttributeId::generate();
    }
}
