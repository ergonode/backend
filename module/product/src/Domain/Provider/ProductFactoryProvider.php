<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Provider;

use Ergonode\Product\Domain\Factory\ProductFactoryInterface;

/**
 */
class ProductFactoryProvider
{
    /**
     * @var ProductFactoryInterface[]
     */
    private array $factories;

    /**
     * @param ProductFactoryInterface ...$factories
     */
    public function __construct(ProductFactoryInterface...$factories)
    {
        $this->factories = $factories;
    }

    /**
     * @param string $type
     *
     * @return ProductFactoryInterface
     */
    public function provide(string $type): ProductFactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->isSupportedBy($type)) {
                return $factory;
            }
        }

        throw new \RuntimeException(sprintf('Can\' provide factory for %s type', $type));
    }
}
