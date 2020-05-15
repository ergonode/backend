<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Provider;

/**
 */
class ExportProcessorProvider
{
    /**
     * @var ExportProcessorInterface ...$processors
     */
    private array $processors;

    /**
     * @param ExportProcessorInterface ...$processors
     */
    public function __construct(ExportProcessorInterface ...$processors)
    {
        $this->processors = $processors;
    }

    /**
     * @param string $type
     *
     * @return ExportProcessorInterface
     */
    public function provide(string $type): ExportProcessorInterface
    {
        foreach ($this->processors as $processor) {
            if ($processor->supported($type)) {
                return $processor;
            }
        }

        throw new \RuntimeException(sprintf('Can\' processor fot type "%s"', $type));
    }
}
