<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Provider;

use Ergonode\Attribute\Infrastructure\Factory\Command\UpdateAttributeCommandFactoryInterface;

class UpdateAttributeCommandFactoryProvider
{
    /**
     * @var UpdateAttributeCommandFactoryInterface[]
     */
    private array $factories;

    public function __construct(UpdateAttributeCommandFactoryInterface ...$factories)
    {
        $this->factories = $factories;
    }

    public function provide(string $type): UpdateAttributeCommandFactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->support($type)) {
                return $factory;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find update attribute command factory for type %s', $type));
    }
}
