<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;

class ProductProductCollectionRelationshipStrategy implements RelationshipStrategyInterface
{
    private const MESSAGE = 'Object has active relationships with {relations}';

    private ProductQueryInterface $query;

    public function __construct(ProductQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AggregateId $id): bool
    {
        return $id instanceof ProductId;
    }

    /**
     * {@inheritDoc}
     */
    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, ProductId::class);

        return new RelationshipGroup(self::MESSAGE, $this->query->findProductCollectionIdByProductId($id));
    }
}
