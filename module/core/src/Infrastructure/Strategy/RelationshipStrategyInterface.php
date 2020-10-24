<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Strategy;

use Ergonode\SharedKernel\Domain\AggregateId;

interface RelationshipStrategyInterface
{
    public function supports(AggregateId $id): bool;

    /**
     * @return AggregateId[]
     */
    public function getRelationships(AggregateId $id): array;
}
