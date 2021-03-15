<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;

interface UserQueryInterface
{
    /**
     * @return string[]
     */
    public function getDictionary(): array;

    public function findIdByEmail(Email $email): ?UserId;
}
