<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;

interface TransformerRepositoryInterface
{
    /**
     * @param TransformerId $id
     *
     * @return bool
     */
    public function exists(TransformerId $id): bool;

    /**
     * @param TransformerId $id
     *
     * @return AbstractAggregateRoot|Transformer
     */
    public function load(TransformerId $id): ?AbstractAggregateRoot;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function delete(AbstractAggregateRoot $aggregateRoot): void;
}
