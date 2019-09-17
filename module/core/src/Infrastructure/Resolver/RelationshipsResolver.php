<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Resolver;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Infrastructure\Model\RelationshipCollection;
use Ergonode\Core\Infrastructure\Strategy\RelationshipStrategyInterface;
use Webmozart\Assert\Assert;

/**
 */
class RelationshipsResolver implements RelationshipsResolverInterface
{
    /**
     * @var iterable
     */
    private $strategies;

    /**
     * @param iterable $strategies
     */
    public function __construct(iterable $strategies)
    {
        Assert::allIsInstanceOf($strategies, RelationshipStrategyInterface::class);

        $this->strategies = $strategies;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve(AbstractId $id): RelationshipCollection
    {
        $collection = new RelationshipCollection();

        /** @var RelationshipStrategyInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($id)) {
                $relationships = $strategy->getRelationships($id);
                if (!empty($relationships)) {
                    $collection->set($strategy->getType(), $relationships);
                }
            }
        }

        return $collection;
    }
}
