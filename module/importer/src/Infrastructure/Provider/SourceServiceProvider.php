<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Provider;

class SourceServiceProvider
{
    /**
     * @var ImportSourceInterface[] $sources
     */
    private array $sources;

    public function __construct(ImportSourceInterface ...$sources)
    {
        $this->sources = $sources;
    }

    public function provide(string $type): ImportSourceInterface
    {
        foreach ($this->sources as $source) {
            if ($source->supported($type)) {
                return $source;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find service for %s type', $type));
    }
}
