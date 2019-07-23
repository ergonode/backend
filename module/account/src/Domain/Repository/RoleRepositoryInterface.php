<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Repository;

use Ergonode\Account\Domain\Entity\Role;
use Ergonode\Account\Domain\Entity\RoleId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;

/**
 */
interface RoleRepositoryInterface
{
    /**
     * @param RoleId $id
     *
     * @return Role|AbstractAggregateRoot|null
     */
    public function load(RoleId $id): ?AbstractAggregateRoot;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;
}
