<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action\Process;

/**
 */
class AttributeImportProcessorProvider
{
    /**
     * @var AttributeImportProcessorStrategyInterface[]
     */
    private array $processors;

    /**
     * @param AttributeImportProcessorStrategyInterface ...$processors
     */
    public function __construct(AttributeImportProcessorStrategyInterface ...$processors)
    {
        $this->processors = $processors;
    }

    /**
     * @param string $type
     *
     * @return AttributeImportProcessorStrategyInterface
     */
    public function provide(string $type): AttributeImportProcessorStrategyInterface
    {
        foreach ($this->processors as $processor) {
            if ($processor->supported($type)) {
                return $processor;
            }
        }

        throw new \RuntimeException('Can\'t fond attribute import processor for %s attribute type', $type);
    }
}
