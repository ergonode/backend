<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Resolver;

use Ergonode\Core\Infrastructure\Model\Relationship;
use Ergonode\SharedKernel\Domain\AggregateId;

interface RelationshipsResolverInterface
{
    public function resolve(AggregateId $id): ?Relationship;
}
