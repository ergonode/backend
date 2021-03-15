<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Infrastructure\Provider;

use Ergonode\Channel\Infrastructure\Processor\ExportProcessorInterface;

class ExportProcessorProvider
{
    /**
     * @var ExportProcessorInterface[] $processors
     */
    private array $processors;

    public function __construct(ExportProcessorInterface ...$processors)
    {
        $this->processors = $processors;
    }

    public function provide(string $type): ExportProcessorInterface
    {
        foreach ($this->processors as $processor) {
            if ($processor->supported($type)) {
                return $processor;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find processor type "%s"', $type));
    }
}
