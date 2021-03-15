<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Resolver;

use Ergonode\Core\Infrastructure\Model\Relationship;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Ergonode\SharedKernel\Domain\AggregateId;

class RelationshipsResolver implements RelationshipsResolverInterface
{
    /**
     * @var RelationshipStrategyInterface[]
     */
    private array $strategies;

    public function __construct(RelationshipStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(AggregateId $id): ?Relationship
    {
        $result = [];

        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($id)) {
                $group = $strategy->getRelationshipGroup($id);
                if (!empty($group->getRelations())) {
                    $result[] = $group;
                }
            }
        }

        if (!empty($result)) {
            return new Relationship($result);
        }

        return null;
    }
}
