<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Provider;

use Ergonode\Importer\Domain\Factory\SourceFactoryInterface;

/**
 */
class SourceFactoryProvider
{
    /**
     * @var SourceFactoryInterface ...$factories
     */
    private array $factories;

    /**
     * @param SourceFactoryInterface ...$factories
     */
    public function __construct(SourceFactoryInterface ...$factories)
    {
        $this->factories = $factories;
    }

    /**
     * @param string $type
     *
     * @return SourceFactoryInterface
     */
    public function provide(string $type): SourceFactoryInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->supported($type)) {
                return $factory;
            }
        }

        throw new \RuntimeException(sprintf('can\'t find source factory for %s type', $type));
    }
}
