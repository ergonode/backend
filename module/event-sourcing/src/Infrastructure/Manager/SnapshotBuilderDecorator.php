<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Manager;

use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Snapshot\AggregateSnapshotInterface;

class SnapshotBuilderDecorator implements AggregateBuilderInterface
{
    private AggregateBuilder $builder;

    private AggregateSnapshotInterface $snapshot;

    public function __construct(
        AggregateBuilder $builder,
        AggregateSnapshotInterface $snapshot
    ) {
        $this->builder = $builder;
        $this->snapshot = $snapshot;
    }

    /**
     * @throws \ReflectionException
     */
    public function build(AggregateId $id, string $class): AbstractAggregateRoot
    {
        $aggregate = $this->snapshot->load($id, $class);
        if ($aggregate) {
            return $aggregate;
        }

        return $this->builder->build($id, $class);
    }
}
