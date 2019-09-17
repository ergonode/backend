<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Condition\Domain\ValueObject\ConditionSetCode;
use Faker\Provider\Base as BaseProvider;

/**
 */
class ConditionSetIdFaker extends BaseProvider
{
    /**
     * @param string|null $code
     *
     * @return ConditionSetId
     *
     * @throws \Exception
     */
    public function conditionSetId(?string $code = null): ConditionSetId
    {
        if ($code) {
            return ConditionSetId::fromCode(new ConditionSetCode($code));
        }

        return ConditionSetId::generate();
    }
}
