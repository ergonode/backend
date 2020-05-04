<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Provider;

use Ergonode\Product\Infrastructure\Factory\Command\CreateProductCommandFactoryInterface;

/**
 */
class CreateProductCommandFactoryProvider
{
    /**
     * @var CreateProductCommandFactoryInterface[]
     */
    private array $factories;

    /**
     * @param CreateProductCommandFactoryInterface ...$factories
     */
    public function __construct(CreateProductCommandFactoryInterface ...$factories)
    {
        $this->factories = $factories;
    }

    /**
     * @param string $type
     *
     * @return CreateProductCommandFactoryInterface
     */
    public function provide(string $type):CreateProductCommandFactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->support($type)) {
                return $factory;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find command factory for "%s" product', $type));
    }
}
