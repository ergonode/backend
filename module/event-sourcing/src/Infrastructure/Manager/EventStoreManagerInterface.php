<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Manager;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\DBALException;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\AggregateId;

interface EventStoreManagerInterface
{
    /**
     * @throws \ReflectionException
     */
    public function load(AggregateId $id): ?AbstractAggregateRoot;

    /**
     * @throws DBALException
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;

    public function exists(AggregateId $id): bool;

    /**
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function delete(AbstractAggregateRoot $aggregateRoot): void;
}
