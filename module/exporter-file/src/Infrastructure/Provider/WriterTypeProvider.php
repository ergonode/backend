<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Provider;

use Ergonode\ExporterFile\Infrastructure\Writer\WriterInterface;

class WriterTypeProvider
{
    /**
     * @var string[]
     */
    private array $types = [];

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
