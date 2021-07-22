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
    private const ONE_MESSAGE = 'Condition set has a relation with a segment';
    private const MULTIPLE_MESSAGE = 'Condition set has %count% relations with some segments';

    private SegmentQueryInterface $query;

    public function __construct(SegmentQueryInterface $query)
    {
        $this->query = $query;
    }

    public function supports(AggregateId $id): bool
    {
        return $id instanceof ConditionSetId;
    }

    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, ConditionSetId::class);

        $relations = $this->query->findIdByConditionSetId($id);
        $message = count($relations) === 1 ? self::ONE_MESSAGE : self::MULTIPLE_MESSAGE;

        return new RelationshipGroup($message, $relations);
    }
}
