<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Faker\Provider\Base as BaseProvider;

/**
 */
class ConditionSetIdFaker extends BaseProvider
{
    /**
     * @param string|null $name
     *
     * @return ConditionSetId
     *
     * @throws \Exception
     */
    public function conditionSetId(?string $name = null): ConditionSetId
    {
        if ($name) {
            return ConditionSetId::fromString($name);
        }

        return ConditionSetId::generate();
    }
}
