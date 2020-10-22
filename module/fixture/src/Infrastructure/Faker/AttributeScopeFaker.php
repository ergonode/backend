<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Faker\Provider\Base as BaseProvider;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;

class AttributeScopeFaker extends BaseProvider
{
    /**
     * @param string|null $scope
     *
     * @return AttributeScope
     *
     * @throws \Exception
     */
    public function attributeScope(?string $scope): AttributeScope
    {
        return new AttributeScope($scope);
    }
}
