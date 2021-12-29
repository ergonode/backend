<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Provider;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Webmozart\Assert\Assert;

class BatchActionFilterIdsProvider
{
    /**
     * @var BatchActionFilterIdsInterface[]
     */
    private iterable $strategies;

    /**
     * @param BatchActionFilterIdsInterface[] $strategies
     */
    public function __construct(iterable $strategies)
    {
        Assert::allIsInstanceOf($strategies, BatchActionFilterIdsInterface::class);
        $this->strategies = $strategies;
    }

    public function provide(BatchActionType $type): BatchActionFilterIdsInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($type)) {
                return $strategy;
            }
        }

        throw new \RuntimeException(
            sprintf('Can\'t find filter strategy for %s batch action type', $type->getValue())
        );
    }
}
