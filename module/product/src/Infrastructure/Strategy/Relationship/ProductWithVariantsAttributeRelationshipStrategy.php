<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Infrastructure\Model\RelationshipGroup;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Product\Domain\Query\ProductWithVariantsQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;

class ProductWithVariantsAttributeRelationshipStrategy implements RelationshipStrategyInterface
{
    private const ONE_MESSAGE = 'This attribute is a binding attribute in product.';
    private const MULTIPLE_MESSAGE = 'This attribute is a binding attribute in %count% products.';

    private ProductWithVariantsQueryInterface $query;

    public function __construct(ProductWithVariantsQueryInterface $query)
    {
        $this->query = $query;
    }

    public function supports(AggregateId $id): bool
    {
        return $id instanceof AttributeId;
    }

    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, AttributeId::class);

        $relations = $this->query->findProductIdsWithBoundAttributeByAttributeId($id);
        $message = count($relations) === 1 ? self::ONE_MESSAGE : self::MULTIPLE_MESSAGE;

        return new RelationshipGroup($message, $relations);
    }
}
