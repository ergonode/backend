<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Provider;

use Ergonode\Exporter\Domain\Factory\ExportProfileFactoryInterface;

/**
 */
class ExportProfileProvider
{
    /**
     * @var ExportProfileFactoryInterface ...$factories
     */
    private array $factories;

    /**
     * @param ExportProfileFactoryInterface ...$factories
     */
    public function __construct(ExportProfileFactoryInterface ...$factories)
    {
        $this->factories = $factories;
    }

    /**
     * @param string $type
     *
     * @return ExportProfileFactoryInterface
     */
    public function provide(string $type): ExportProfileFactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->supported($type)) {
                return $factory;
            }
        }
        throw new \RuntimeException(sprintf('can\'t find export profile factory for %s type', $type));
    }
}
