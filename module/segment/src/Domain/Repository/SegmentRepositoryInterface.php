<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\Segment\Domain\Entity\SegmentId;

/**
 */
interface SegmentRepositoryInterface
{
    /**
     * @param SegmentId $id
     *
     * @return Segment|null
     */
    public function load(SegmentId $id): ?AbstractAggregateRoot;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;

    /**
     * @param SegmentId $id
     *
     * @return bool
     */
    public function exists(SegmentId $id): bool;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function delete(AbstractAggregateRoot $aggregateRoot): void;
}
