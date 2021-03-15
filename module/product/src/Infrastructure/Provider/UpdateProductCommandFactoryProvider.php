<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Provider;

use Ergonode\Product\Infrastructure\Factory\Command\UpdateProductCommandFactoryInterface;

class UpdateProductCommandFactoryProvider
{
    /**
     * @var UpdateProductCommandFactoryInterface[]
     */
    private array $factories;

    public function __construct(UpdateProductCommandFactoryInterface ...$factories)
    {
        $this->factories = $factories;
    }

    public function provide(string $type): UpdateProductCommandFactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->support($type)) {
                return $factory;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find command factory for "%s" product', $type));
    }
}
