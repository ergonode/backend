<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Resolver;

use Ergonode\Core\Infrastructure\Model\RelationshipCollection;
use Ergonode\SharedKernel\Domain\AggregateId;

interface RelationshipsResolverInterface
{
    /**
     * @param AggregateId $id
     *
     * @return RelationshipCollection
     */
    public function resolve(AggregateId $id): RelationshipCollection;
}
