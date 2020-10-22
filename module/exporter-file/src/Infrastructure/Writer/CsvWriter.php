<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Writer;

use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;

class CsvWriter implements WriterInterface
{
    public const TYPE = 'csv';

    /**
     * @param string $type
     *
     * @return bool
     */
    public function support(string $type): bool
    {
        return self::TYPE === $type;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param array $headers
     *
     * @return string[]
     */
    public function header(array $headers): array
    {
        return [$this->formatLine($headers)];
    }

    /**
     * @param ExportData $data
     *
     * @return array
     */
    public function add(ExportData $data): array
    {
        $result = [];
        foreach ($data->getLanguages() as $language) {
            $result[] = $this->formatLine($language->getValues());
        }

        return $result;
    }

    /**
     * @param array $attributes
     *
     * @return string[]
     */
    public function footer(array $attributes): array
    {
        return [];
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function formatLine(array $data): string
    {
        $buffer = fopen('php://temp', 'rb+');

        fputcsv($buffer, $data);

        rewind($buffer);
        $csv = fgets($buffer);
        fclose($buffer);

        return $csv;
    }
}
