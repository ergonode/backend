<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Provider;

class SourceFormFactoryProvider
{
    /**
     * @var SourceFormFactoryInterface[]
     */
    private array $factories;

    /**
     * @param SourceFormFactoryInterface ...$factories
     */
    public function __construct(SourceFormFactoryInterface ...$factories)
    {
        $this->factories = $factories;
    }

    /**
     * @param string $type
     *
     * @return SourceFormFactoryInterface
     */
    public function provide(string $type): SourceFormFactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->supported($type)) {
                return $factory;
            }
        }

        throw new \RuntimeException(sprintf('Can\' find form factory for "%s" source type', $type));
    }
}
