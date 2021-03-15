<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Importer\Domain\Entity\Transformer;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;

interface TransformerRepositoryInterface
{
    public function exists(TransformerId $id): bool;

    /**
     * @return AbstractAggregateRoot|Transformer
     */
    public function load(TransformerId $id): ?AbstractAggregateRoot;

    public function save(AbstractAggregateRoot $aggregateRoot): void;

    public function delete(AbstractAggregateRoot $aggregateRoot): void;
}
