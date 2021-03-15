<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Provider;

use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Webmozart\Assert\Assert;

class BatchActionProcessorProvider
{
    /**
     * @var BatchActionProcessorInterface[]
     */
    private iterable $strategies;

    /**
     * @param BatchActionProcessorInterface[] $strategies
     */
    public function __construct(iterable $strategies)
    {
        Assert::allIsInstanceOf($strategies, BatchActionProcessorInterface::class);

        $this->strategies = $strategies;
    }

    public function supports(BatchActionType $type): bool
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($type)) {
                return true;
            }
        }

        return false;
    }

    public function provide(BatchActionType $type): BatchActionProcessorInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($type)) {
                return $strategy;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find strategy for %s batch action type', $type->getValue()));
    }
}
