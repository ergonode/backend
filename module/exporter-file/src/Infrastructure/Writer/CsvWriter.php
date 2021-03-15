<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Writer;

use Ergonode\ExporterFile\Infrastructure\DataStructure\ExportData;

class CsvWriter implements WriterInterface
{
    public const TYPE = 'csv';

    public function support(string $type): bool
    {
        return self::TYPE === $type;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param string[] $headers
     *
     * @return string[]
     */
    public function header(array $headers): array
    {
        return [$this->formatLine($headers)];
    }

    /**
     * @return array
     */
    public function add(ExportData $data): array
    {
        $result = [];
        foreach ($data->getLines() as $line) {
            $result[] = $this->formatLine($line->getValues());
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
