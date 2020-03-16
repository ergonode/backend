<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Query;

use Ergonode\SharedKernel\Domain\ValueObject\Email;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

/**
 */
interface UserQueryInterface
{
    /**
     * @return string[]
     */
    public function getDictionary(): array;

    /**
     * @param Email $email
     *
     * @return UserId|null
     */
    public function findIdByEmail(Email $email): ?UserId;
}
