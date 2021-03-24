<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Repository;

use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

interface StatusRepositoryInterface
{
    /**
     *
     * @throws \ReflectionException
     */
    public function load(StatusId $id): ?Status;

    public function exists(StatusId $id): bool;

    public function save(Status $aggregateRoot): void;

    public function delete(Status $aggregateRoot): void;
}
