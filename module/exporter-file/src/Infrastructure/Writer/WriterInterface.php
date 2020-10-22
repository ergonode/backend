<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Writer;

use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;

interface WriterInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function support(string $type): bool;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param array $header
     *
     * @return string[]
     */
    public function header(array $header): array;

    /**
     * @param ExportData $line
     *
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
