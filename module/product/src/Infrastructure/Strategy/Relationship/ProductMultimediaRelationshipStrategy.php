<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 */
class ProductMultimediaRelationshipStrategy implements RelationshipStrategyInterface
{
    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $productQuery;

    /**
     * @param ProductQueryInterface $productQuery
     */
    public function __construct(ProductQueryInterface $productQuery)
    {
        $this->productQuery = $productQuery;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AggregateId $id): bool
    {
        return $id instanceof MultimediaId;
    }

    /**
     * {@inheritDoc}
     */
    public function getRelationships(AggregateId $id): array
    {
        if (!$this->supports($id)) {
            throw new UnexpectedTypeException($id, MultimediaId::class);
        }
        $result = [];

        $list = $this->productQuery->getMultimediaRelation($id);
        foreach (array_keys($list) as $productId) {
            $result[] = new ProductId($productId);
        }

        return $result;
    }
}
