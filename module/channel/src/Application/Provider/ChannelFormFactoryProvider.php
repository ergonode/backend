<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Provider;

class ChannelFormFactoryProvider
{
    /**
     * @var ChannelFormFactoryInterface[]
     */
    private array $factories;

    public function __construct(ChannelFormFactoryInterface ...$factories)
    {
        $this->factories = $factories;
    }

    public function provide(string $type): ChannelFormFactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->supported($type)) {
                return $factory;
            }
        }
        throw new \RuntimeException(sprintf('Can\' find form factory for "%s" channel form', $type));
    }
}
