<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Relationship;

use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionQueryInterface;

class ProductProductCollectionRelationshipStrategy implements RelationshipStrategyInterface
{
    private const MESSAGE = 'Object has active relationships with product collection %relations%';

    private ProductCollectionQueryInterface $query;

    public function __construct(ProductCollectionQueryInterface $query)
    {
        $this->query = $query;
    }

    public function supports(AggregateId $id): bool
    {
        return $id instanceof ProductId;
    }

    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, ProductId::class);

        return new RelationshipGroup(self::MESSAGE, $this->query->findProductCollectionIdByProductId($id));
    }
}
