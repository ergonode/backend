<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;

class ProductMultimediaRelationshipStrategy implements RelationshipStrategyInterface
{
    private const ONE_MESSAGE = 'Multimedia have a relation with a product';
    private const MULTIPLE_MESSAGE = 'Multimedia have %count% relations with some products';

    private ProductQueryInterface $productQuery;

    public function __construct(ProductQueryInterface $productQuery)
    {
        $this->productQuery = $productQuery;
    }

    public function supports(AggregateId $id): bool
    {
        return $id instanceof MultimediaId;
    }

    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, MultimediaId::class);

        $relations = [];

        $list = $this->productQuery->getMultimediaRelation($id);
        foreach (array_keys($list) as $productId) {
            $relations[] = new ProductId($productId);
        }

        $message = count($relations) === 1 ? self::ONE_MESSAGE : self::MULTIPLE_MESSAGE;

        return new RelationshipGroup($message, $relations);
    }
}
