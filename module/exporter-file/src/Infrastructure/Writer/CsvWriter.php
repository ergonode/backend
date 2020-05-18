<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Writer;

/**
 */
class CsvWriter implements WriterInterface
{
    public const TYPE = 'csv';

    public const DELIMITER = 'delimiter';
    public const ENCLOSURE = 'enclosure';
    public const ESCAPE = 'escape';

    public const DEFAULT = [
        self::DELIMITER => ',',
        self::ENCLOSURE => '"',
        self::ESCAPE => '\\',
    ];

    /**
     * @var mixed
     */
    private $file;

    /**
     * @var array
     */
    private array $configuration;

    /**
     * @param string $file
     * @param array  $configuration
     */
    public function open(string $file, array $configuration = []): void
    {
        $this->configuration = array_merge(self::DEFAULT, $configuration);

        $this->file = \fopen($file, 'rb');
        if (false === $this->file) {
            throw new \RuntimeException(sprintf('cant\' open "%s" file', $file));
        }
    }

    /**
     * @param array    $data
     */
    public function onStart(array $data): void
    {
        $headers = array_keys($data);

        $result = $this->putCSV($headers);

        if (false === $result) {
            throw new \RuntimeException(sprintf('can\'t write to csv file'));
        }
    }

    /**
     * @param array $data
     */
    public function write(array $data): void
    {
        $result = $this->putCSV($data);

        if (false === $result) {
            throw new \RuntimeException(sprintf('can\'t write to csv file'));
        }
    }


    /**
     * @param array $data
     *
     * @return false|int
     */
    private function putCSV(array $data)
    {
        return fputcsv(
            $this->file,
            $data,
            $this->configuration[self::DELIMITER],
            $this->configuration[self::ENCLOSURE],
            $this->configuration[self::ESCAPE],
        );
    }
}