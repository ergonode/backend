<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\DataStructure;

class ExportData
{
    /**
     * @var ExportLineData[]
     */
    private array $lines = [];

    public function add(ExportLineData $line): void
    {
        $this->lines[] = $line;
    }

    /**
     * @return ExportLineData[]
     */
    public function getLines(): array
    {
        return $this->lines;
    }
}
