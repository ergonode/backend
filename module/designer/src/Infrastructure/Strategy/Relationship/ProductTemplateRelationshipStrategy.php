<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Strategy\Relationship;

use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Webmozart\Assert\Assert;
use Ergonode\Core\Infrastructure\Model\RelationshipGroup;

class ProductTemplateRelationshipStrategy implements RelationshipStrategyInterface
{
    private const ONE_MESSAGE = 'Template has a relation with a product';
    private const MULTIPLE_MESSAGE = 'Template has %count% relations with some products';

    private TemplateQueryInterface $query;

    public function __construct(TemplateQueryInterface $query)
    {
        $this->query = $query;
    }

    public function supports(AggregateId $id): bool
    {
        return $id instanceof TemplateId;
    }

    public function getRelationshipGroup(AggregateId $id): RelationshipGroup
    {
        Assert::isInstanceOf($id, TemplateId::class);

        $relations = $this->query->findProductIdByTemplateId($id);
        $message = count($relations) === 1 ? self::ONE_MESSAGE : self::MULTIPLE_MESSAGE;

        return new RelationshipGroup($message, $relations);
    }
}
