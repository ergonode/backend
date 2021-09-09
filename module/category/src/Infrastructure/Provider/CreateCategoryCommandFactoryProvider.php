<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Provider;

use Ergonode\Category\Infrastructure\Factory\Command\CreateCategoryCommandFactoryInterface;

class CreateCategoryCommandFactoryProvider
{
    /**
     * @var CreateCategoryCommandFactoryInterface[]
     */
    private array $factories;

    public function __construct(CreateCategoryCommandFactoryInterface ...$factories)
    {
        $this->factories = $factories;
    }

    public function provide(string $type): CreateCategoryCommandFactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->support($type)) {
                return $factory;
            }
        }

        throw new \RuntimeException(
            sprintf(
                'Can\'t find create category command factory for type %s',
                get_class($this->factories[0])
            )
        );
    }
}
