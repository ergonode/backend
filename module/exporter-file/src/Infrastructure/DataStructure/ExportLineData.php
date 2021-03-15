<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\DataStructure;

class ExportLineData
{
    /**
     * @var string[]
     */
    private array $values = [];

    public function set(string $key, ?string $data = null): void
    {
        $this->values[$key] = $data;
    }

    /**
     * @return string[]
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
