<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Provider;

use Ergonode\Importer\Infrastructure\Processor\SourceImportProcessorInterface;

/**
 */
class ImportProcessorProvider
{
    /**
     * @var SourceImportProcessorInterface[]
     */
    private array $processors;

    /**
     * @param SourceImportProcessorInterface ...$processors
     */
    public function __construct(SourceImportProcessorInterface ...$processors)
    {
        $this->processors = $processors;
    }

    /**
     * @param string $type
     *
     * @return SourceImportProcessorInterface
     */
    public function provide(string $type): SourceImportProcessorInterface
    {
        foreach ($this->processors as $processor) {
            if ($processor->supported($type)) {
                return $processor;
            }
        }

        throw new \RuntimeException(sprintf('Can\' processor fot type "%s"', $type));
    }
}
