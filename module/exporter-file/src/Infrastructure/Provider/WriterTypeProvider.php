<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Provider;

use Ergonode\ExporterFile\Infrastructure\Writer\WriterInterface;

/**
 */
class WriterTypeProvider
{
    /**
     * @var string[]
     */
    private array $types;

    /**
     * @param WriterInterface ...$writers
     */
    public function __construct(WriterInterface ...$writers)
    {
        foreach ($writers as $writer) {
            $this->types[] = $writer->getType();
        }
    }

    /**
     * @return string[]
     */
    public function provide(): array
    {
        return $this->types;
    }
}
