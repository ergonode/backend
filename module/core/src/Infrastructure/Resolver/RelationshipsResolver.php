<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Resolver;

use Ergonode\Core\Infrastructure\Model\RelationshipCollection;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\SharedKernel\Domain\AggregateId;

/**
 */
class RelationshipsResolver implements RelationshipsResolverInterface
{
    /**
     * @var RelationshipStrategyInterface[]
     */
    private array $strategies;

    /**
     * @param RelationshipStrategyInterface ...$strategies
     */
    public function __construct(RelationshipStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(AggregateId $id): RelationshipCollection
    {
        $collection = new RelationshipCollection();

        /** @var RelationshipStrategyInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($id)) {
                $relationships = $strategy->getRelationships($id);
                foreach ($relationships as $relationship) {
                    $collection->add($relationship);
                }
            }
        }

        return $collection;
    }
}
