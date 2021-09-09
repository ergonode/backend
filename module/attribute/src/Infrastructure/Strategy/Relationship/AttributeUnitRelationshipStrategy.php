<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Strategy\Relationship;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;

class AttributeUnitRelationshipStrategy implements RelationshipStrategyInterface
{
    private const ONE_MESSAGE = 'Unit has a relation with an attribute';
    private const MULTIPLE_MESSAGE = 'Unit has %count% relations with some attributes';

    private AttributeQueryInterface $query;

    public function __construct(AttributeQueryInterface $query)
    {
        $this->query = $query;
    }

    public function supports(AggregateId $id): bool
    {
        return $id instanceof UnitId;
    }

    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, UnitId::class);

        $relations = $this->query->findAttributeIdsByUnitId($id);
        $message = count($relations) === 1 ? self::ONE_MESSAGE : self::MULTIPLE_MESSAGE;

        return new RelationshipGroup($message, $relations);
    }
}
