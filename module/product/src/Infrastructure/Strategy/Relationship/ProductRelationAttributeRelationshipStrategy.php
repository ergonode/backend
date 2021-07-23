<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;
use Ergonode\Product\Domain\Query\ProductRelationAttributeQueryInterface;

class ProductRelationAttributeRelationshipStrategy implements RelationshipStrategyInterface
{
    private const ONE_MESSAGE = 'Product has a relation with another product';
    private const MULTIPLE_MESSAGE = 'Product has %count% relations with other products';

    private ProductRelationAttributeQueryInterface $query;

    public function __construct(ProductRelationAttributeQueryInterface $query)
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

        $relations =  $this->query->findProductRelatedIds($id);
        $message = count($relations) === 1 ? self::ONE_MESSAGE : self::MULTIPLE_MESSAGE;

        return new RelationshipGroup($message, $relations);
    }
}
