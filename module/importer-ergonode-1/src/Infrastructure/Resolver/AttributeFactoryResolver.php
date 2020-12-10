<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Resolver;

use Ergonode\ImporterErgonode1\Infrastructure\Factory\Attribute\AttributeFactoryInterface;

class AttributeFactoryResolver
{
    private iterable $factories;

    public function __construct(iterable $factories)
    {
        $this->factories = $factories;
    }

    /**
     * @throws \RuntimeException
     */
    public function resolve(string $type): AttributeFactoryInterface
    {
        /** @var AttributeFactoryInterface $factory */
        foreach ($this->factories as $factory) {
            if ($factory->supports($type)) {
                return $factory;
            }
        }

        throw new \RuntimeException("Attribute factory by type \"$type\" not found");
    }
}
