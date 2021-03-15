<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Snapshot;

use Doctrine\DBAL\DBALException;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\AggregateId;

interface AggregateSnapshotInterface
{
    /**
     * @throws \ReflectionException
     */
    public function load(AggregateId $id, string $class): ?AbstractAggregateRoot;

    /**
     * @throws DBALException
     */
    public function save(AbstractAggregateRoot $aggregate): void;

    public function delete(AggregateId $id): void;
}
