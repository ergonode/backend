<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Exporter\Infrastructure\Provider;

use Ergonode\Exporter\Infrastructure\Factory\Command\RemoveExportArtifactsCommandFactoryInterface;

class RemoveExportArtifactsCommandFactoryProvider
{
    private iterable $factories;

    public function __construct(iterable $factories)
    {
        $this->factories = $factories;
    }

    public function provide(string $type): RemoveExportArtifactsCommandFactoryInterface
    {
        /** @var RemoveExportArtifactsCommandFactoryInterface $factory */
        foreach ($this->factories as $factory) {
            if ($factory->support($type)) {
                return $factory;
            }
        }

        throw new \RuntimeException(
            sprintf('Can\'t find remove export files command factory for type %s', $type)
        );
    }
}
