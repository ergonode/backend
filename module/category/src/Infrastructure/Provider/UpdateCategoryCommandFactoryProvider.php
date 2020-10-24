<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Provider;

use Ergonode\Category\Infrastructure\Factory\Command\UpdateCategoryCommandFactoryInterface;

class UpdateCategoryCommandFactoryProvider
{
    /**
     * @var UpdateCategoryCommandFactoryInterface[]
     */
    private array $factories;

    public function __construct(UpdateCategoryCommandFactoryInterface ...$factories)
    {
        $this->factories = $factories;
    }

    public function provide(string $type): UpdateCategoryCommandFactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->support($type)) {
                return $factory;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find update category command factory for type %s', $type));
    }
}
