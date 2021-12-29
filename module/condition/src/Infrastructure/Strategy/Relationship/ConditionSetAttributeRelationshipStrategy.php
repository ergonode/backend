<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Strategy\Relationship;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Condition\Domain\Query\ConditionSetQueryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;

class ConditionSetAttributeRelationshipStrategy implements RelationshipStrategyInterface
{
    private const ONE_MESSAGE = 'Attribute is used in one condition set';
    private const MULTIPLE_MESSAGE = 'Attribute is used in %count% condition sets';

    private ConditionSetQueryInterface $query;

    public function __construct(ConditionSetQueryInterface $query)
    {
        $this->query = $query;
    }

    public function supports(AggregateId $id): bool
    {
        return $id instanceof AttributeId;
    }

    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, AttributeId::class);

        $relations = $this->query->findAttributeIdConditionRelations($id);
        $message = count($relations) === 1 ? self::ONE_MESSAGE : self::MULTIPLE_MESSAGE;

        return new RelationshipGroup($message, $relations);
    }
}
