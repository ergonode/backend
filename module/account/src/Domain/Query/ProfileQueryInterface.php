<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;

interface ProfileQueryInterface
{
    /**
     * @return array
     */
    public function getProfile(UserId $userId): array;
}
