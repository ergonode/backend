<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Provider;

use Ergonode\Product\Infrastructure\Strategy\ProductFactoryStrategyInterface;
use Webmozart\Assert\Assert;

class ProductStrategyProvider
{
    /**
     * @var ProductFactoryStrategyInterface[]
     */
    private iterable $productStrategies;

    public function __construct(iterable $productStrategies)
    {
        Assert::allIsInstanceOf($productStrategies, ProductFactoryStrategyInterface::class);

        $this->productStrategies = $productStrategies;
    }

    public function provide(string $type): ProductFactoryStrategyInterface
    {
        foreach ($this->productStrategies as $productStrategy) {
            if ($productStrategy->supports($type)) {
                return $productStrategy;
            }
        }
        throw new \RuntimeException(
            sprintf('Can\'t find Product strategy for %s', $type)
        );
    }
}
