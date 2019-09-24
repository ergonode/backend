<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Condition\Domain\ValueObject\ConditionSetCode;
use Faker\Provider\Base as BaseProvider;

/**
 */
class ConditionSetCodeFaker extends BaseProvider
{
    /**
     * @param string|null $code
     *
     * @return ConditionSetCode
     *
     * @throws \Exception
     */
    public function conditionSetCode(?string $code = null): ConditionSetCode
    {
        if ($code) {
            return new ConditionSetCode($code);
        }

        return new ConditionSetCode(sprintf('code_%s_%s', random_int(1, 1000000), random_int(1, 1000000)));
    }
}
