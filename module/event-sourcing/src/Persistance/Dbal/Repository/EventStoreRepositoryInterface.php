<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Persistance\Dbal\Repository;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\Stream\DomainEventStream;

/**
 */
interface EventStoreRepositoryInterface
{
    /**
     * @param AbstractId $id
     * @param int        $sequence
     *
     * @return array
     */
    public function load(AbstractId $id, int $sequence = 0): array;

    /**
     * @param AbstractId $id
     *
     * @return bool
     */
    public function exists(AbstractId $id): bool;

    /**
     * @param AbstractId        $id
     * @param DomainEventStream $stream
     * @param UserId|null       $userId
     */
    public function append(AbstractId $id, DomainEventStream $stream, ?UserId $userId = null): void;

    /**
     * @param AbstractId $id
     */
    public function delete(AbstractId $id): void;
}
