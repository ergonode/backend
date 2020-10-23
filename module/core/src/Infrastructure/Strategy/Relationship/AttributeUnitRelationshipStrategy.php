<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Strategy\Relationship;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class AttributeUnitRelationshipStrategy implements RelationshipStrategyInterface
{
    private AttributeQueryInterface $query;

    public function __construct(AttributeQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AggregateId $id): bool
    {
        return $id instanceof UnitId;
    }

    /**
     * {@inheritDoc}
     */
    public function getRelationships(AggregateId $id): array
    {
        if (!$this->supports($id)) {
            throw new UnexpectedTypeException($id, UnitId::class);
        }

        return $this->query->findAttributeIdsByUnitId($id);
    }
}
