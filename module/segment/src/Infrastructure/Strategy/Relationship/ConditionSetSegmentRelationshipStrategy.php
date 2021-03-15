<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Strategy\Relationship;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;

class ConditionSetSegmentRelationshipStrategy implements RelationshipStrategyInterface
{
    private const MESSAGE = 'Object has active relationships with segment %relations%';

    private SegmentQueryInterface $query;

    public function __construct(SegmentQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AggregateId $id): bool
    {
        return $id instanceof ConditionSetId;
    }

    /**
     * {@inheritDoc}
     */
    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, ConditionSetId::class);

        return new RelationshipGroup(self::MESSAGE, $this->query->findIdByConditionSetId($id));
    }
}
