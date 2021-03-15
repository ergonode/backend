<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Strategy;

use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;

interface RelationshipStrategyInterface
{
    public function supports(AggregateId $id): bool;

    public function getRelationshipGroup(AggregateId $id): RelationshipGroup;
}
