<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

/**
 */
class ConditionSetIdFaker extends BaseProvider
{
    private const NAMESPACE = '14343bf2-6c4c-47cc-92fc-3002a09521fc';

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
            return new ConditionSetId(Uuid::uuid5(self::NAMESPACE, $name)->toString());
        }

        return ConditionSetId::generate();
    }
}
