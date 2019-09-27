<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Strategy\Relationship;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 */
class ProductCategoryRelationshipStrategy implements RelationshipStrategyInterface
{
    /**
     * @var ProductQueryInterface
     */
    private $query;

    /**
     * @param ProductQueryInterface $query
     */
    public function __construct(ProductQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AbstractId $id): bool
    {
        return $id instanceof CategoryId;
    }

    /**
     * {@inheritDoc}
     */
    public function getRelationships(AbstractId $id): array
    {
        if (!$this->supports($id)) {
            throw new UnexpectedTypeException($id, CategoryId::class);
        }

        return $this->query->findProductIdByCategoryId($id);
    }
}
