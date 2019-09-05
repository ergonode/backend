<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Persistance\Provider;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;

/**
 */
interface AggregateRootProviderInterface
{
    /**
     * @param AbstractId $id
     * @param string     $class
     *
     * @return AbstractAggregateRoot|null
     */
    public function load(AbstractId $id, string $class): ?AbstractAggregateRoot;

    /**
     * @param AbstractId $id
     *
     * @return bool
     */
    public function exists(AbstractId $id): bool;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function delete(AbstractAggregateRoot $aggregateRoot): void;
}
