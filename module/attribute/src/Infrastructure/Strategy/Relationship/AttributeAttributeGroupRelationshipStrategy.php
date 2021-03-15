<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Strategy\Relationship;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;

class AttributeAttributeGroupRelationshipStrategy implements RelationshipStrategyInterface
{
    private const MESSAGE = 'Object has active relationships with attribute %relations%';

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
        return $id instanceof AttributeGroupId;
    }

    /**
     * {@inheritDoc}
     */
    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, AttributeGroupId::class);

        return new RelationshipGroup(self::MESSAGE, $this->query->findAttributeIdsByAttributeGroupId($id));
    }
}
