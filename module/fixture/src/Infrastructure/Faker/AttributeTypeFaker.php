<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Faker\Provider\Base as BaseProvider;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;

class AttributeTypeFaker extends BaseProvider
{
    public function attributeType(string $type): AttributeType
    {
        return new AttributeType($type);
    }
}
