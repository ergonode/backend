<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Product\Domain\Query\ProductChildrenQueryInterface;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 */
class ChildrenProductRelationshipStrategy implements RelationshipStrategyInterface
{
    /**
     * @var ProductChildrenQueryInterface
     */
    private ProductChildrenQueryInterface $query;

    /**
     * @param ProductChildrenQueryInterface $query
     */
    public function __construct(ProductChildrenQueryInterface $query)
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
    public function getRelationships(AggregateId $id): array
    {
        if (!$this->supports($id)) {
            throw new UnexpectedTypeException($id, ProductId::class);
        }

        return $this->query->findProductIdByProductChildrenId($id);
    }
}
