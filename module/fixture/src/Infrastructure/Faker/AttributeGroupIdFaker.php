<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Faker\Provider\Base as BaseProvider;

/**
 */
class AttributeGroupIdFaker extends BaseProvider
{
    /**
     * @return AttributeGroupId
     *
     * @throws \Exception
     */
    public function attributeGroupId(): AttributeGroupId
    {
        return AttributeGroupId::generate();
    }
}
