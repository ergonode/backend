<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;

interface ProfileQueryInterface
{
    /**
     * @param UserId $userId
     *
     * @return array
     */
    public function getProfile(UserId $userId): array;
}
