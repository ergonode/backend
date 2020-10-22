<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Strategy\Relationship;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ConditionSetSegmentRelationshipStrategy implements RelationshipStrategyInterface
{
    /**
     * @var SegmentQueryInterface
     */
    private SegmentQueryInterface $query;

    /**
     * @param SegmentQueryInterface $query
     */
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
    public function getRelationships(AggregateId $id): array
    {
        if (!$this->supports($id)) {
            new UnexpectedTypeException($id, ConditionSetId::class);
        }

        return $this->query->findIdByConditionSetId($id);
    }
}
