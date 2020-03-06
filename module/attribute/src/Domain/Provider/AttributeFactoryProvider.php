<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Provider;

use Ergonode\Attribute\Domain\AttributeFactoryInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;

/**
 */
class AttributeFactoryProvider
{
    /**
     * @var AttributeFactoryInterface[]
     */
    private array $factories;

    /**
     * @param AttributeFactoryInterface ...$factories
     */
    public function __construct(AttributeFactoryInterface ...$factories)
    {
        $this->factories = $factories;
    }

    /**
     * @param AttributeType $type
     *
     * @return AttributeFactoryInterface
     */
    public function provide(AttributeType $type): AttributeFactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($type)) {
                return $factory;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find factory for attribute %s', $type->getValue()));
    }
}
