<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Provider;

use Ergonode\Attribute\Infrastructure\Factory\Command\CreateAttributeCommandFactoryInterface;

/**
 */
class CreateAttributeCommandFactoryProvider
{
    /**
     * @var CreateAttributeCommandFactoryInterface[]
     */
    private array $factories;

    /**
     * @param CreateAttributeCommandFactoryInterface ...$factories
     */
    public function __construct(CreateAttributeCommandFactoryInterface ...$factories)
    {
        $this->factories = $factories;
    }

    /**
     * @param string $type
     *
     * @return CreateAttributeCommandFactoryInterface
     */
    public function provide(string $type): CreateAttributeCommandFactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->support($type)) {
                return $factory;
            }
        }

        throw new \RuntimeException(
            sprintf('Can\'t find create attribute command factory for type %s', $type)
        );
    }
}
