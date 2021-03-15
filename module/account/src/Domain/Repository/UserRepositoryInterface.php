<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Repository;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

interface UserRepositoryInterface
{
    public function load(UserId $id): ?User;

    public function save(User $aggregateRoot): void;
}
