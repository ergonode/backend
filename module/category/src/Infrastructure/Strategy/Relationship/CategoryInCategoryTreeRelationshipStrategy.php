<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Domain\Query\TreeQueryInterface;

class CategoryInCategoryTreeRelationshipStrategy implements RelationshipStrategyInterface
{
    private const MESSAGE = 'Category has relationships with category tree';

    private TreeQueryInterface $query;

    public function __construct(TreeQueryInterface $query)
    {
        $this->query = $query;
    }

    public function supports(AggregateId $id): bool
    {
        return $id instanceof CategoryId;
    }

    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, CategoryId::class);

        return new RelationshipGroup(self::MESSAGE, $this->query->findCategoryTreeIdsByCategoryId($id));
    }
}
