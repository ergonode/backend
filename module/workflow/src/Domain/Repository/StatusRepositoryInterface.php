<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Entity\StatusId;

/**
 */
interface StatusRepositoryInterface
{
    /**
     * @param StatusId $id
     *
     * @return null|Status
     *
     * @throws \ReflectionException
     */
    public function load(StatusId $id): ?AbstractAggregateRoot;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;

    /**
     * @param StatusId $id
     *
     * @return bool
     */
    public function exists(StatusId $id): bool;
}
