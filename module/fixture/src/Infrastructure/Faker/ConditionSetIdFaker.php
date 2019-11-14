<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Faker\Provider\Base as BaseProvider;

/**
 */
class ConditionSetIdFaker extends BaseProvider
{
    /**
     * @param string|null $uuid
     *
     * @return ConditionSetId
     *
     * @throws \Exception
     */
    public function conditionSetId(?string $uuid = null): ConditionSetId
    {
        if ($uuid) {
            return new ConditionSetId($uuid);
        }

        return ConditionSetId::generate();
    }
}
