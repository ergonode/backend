<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\Provider;

/**
 */
class ExportProfileFormFactoryProvider
{
    /**
     * @var ExportProfileFormFactoryInterface[]
     */
    private array $factories;

    /**
     * @param ExportProfileFormFactoryInterface ...$factories
     */
    public function __construct(ExportProfileFormFactoryInterface ...$factories)
    {
        $this->factories = $factories;
    }

    /**
     * @param string $type
     *
     * @return ExportProfileFormFactoryInterface
     */
    public function provide(string $type): ExportProfileFormFactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->supported($type)) {
                return $factory;
            }
        }
        throw new \RuntimeException(sprintf('Can\' find form factory for "%s" export profile type', $type));
    }
}
