<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Manager;

use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\EventSourcing\Infrastructure\Snapshot\AggregateSnapshot;

/**
 */
class SnapshotBuilderDecorator implements AggregateBuilderInterface
{
    /**
     * @var AggregateBuilder
     */
    private AggregateBuilder $builder;

    /**
     * @var AggregateSnapshot
     */
    private AggregateSnapshot $snapshot;

    /**
     * @param AggregateBuilder  $builder
     * @param AggregateSnapshot $snapshot
     */
    public function __construct(
        AggregateBuilder $builder,
        AggregateSnapshot $snapshot
    ) {
        $this->builder = $builder;
        $this->snapshot = $snapshot;
    }

    /**
     * @param AggregateId $id
     * @param string      $class
     *
     * @return AbstractAggregateRoot|null
     *
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
