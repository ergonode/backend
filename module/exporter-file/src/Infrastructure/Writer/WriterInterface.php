<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Writer;

use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;

interface WriterInterface
{
    public function support(string $type): bool;

    public function getType(): string;

    /**
     * @param array $header
     *
     * @return string[]
     */
    public function header(array $header): array;

    /**
     * @return string[]
     */
    public function add(ExportData $line): array;

    /**
     * @param array $line
     *
     * @return string[]
     */
    public function footer(array $line): array;
}
