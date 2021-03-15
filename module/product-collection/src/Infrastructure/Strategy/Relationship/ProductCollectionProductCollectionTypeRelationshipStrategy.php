<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionTypeId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;

class ProductCollectionProductCollectionTypeRelationshipStrategy implements RelationshipStrategyInterface
{
    private const MESSAGE = 'Object has active relationships with collection %relations%';

    private ProductCollectionQueryInterface $query;

    public function __construct(ProductCollectionQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AggregateId $id): bool
    {
        return $id instanceof ProductCollectionTypeId;
    }

    /**
     * {@inheritDoc}
     */
    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, ProductCollectionTypeId::class);

        return new RelationshipGroup(self::MESSAGE, $this->query->findCollectionIdsByCollectionTypeId($id));
    }
}
