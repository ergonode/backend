<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Resolver;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Infrastructure\Strategy\RelationStrategyInterface;

/**
 */
class RelationResolver implements RelationResolverInterface
{
    /**
     * @var RelationStrategyInterface[]
     */
    private $strategies;

    /**
     * @param RelationStrategyInterface ...$strategies
     */
    public function __construct(RelationStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(AbstractId $id): array
    {
        $result = [];

        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($id)) {
                $relations = $strategy->getRelations($id);
                if (!empty($relations)) {
                    $result[$strategy->getType()] = $relations;
                }
            }
        }

        return $result;
    }
}
