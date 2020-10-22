<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Strategy;

use Ergonode\SharedKernel\Domain\AggregateId;

interface RelationshipStrategyInterface
{
    /**
     * @param AggregateId $id
     *
     * @return bool
     */
    public function supports(AggregateId $id): bool;

    /**
     * @param AggregateId $id
     *
     * @return AggregateId[]
     */
    public function getRelationships(AggregateId $id): array;
}
