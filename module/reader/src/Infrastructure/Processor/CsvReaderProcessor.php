<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Reader\Infrastructure\Processor;

use Ergonode\Reader\Infrastructure\FormatterInterface;
use Ergonode\Reader\Infrastructure\ReaderProcessorInterface;
use Ergonode\Reader\Infrastructure\Exception\ReaderException;

class CsvReaderProcessor implements ReaderProcessorInterface
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

    private int $count = 0;

    /**
     * @var array
     */
    private array $headers = [];

    /**
     * @var array
     */
    private array $configuration;

    /**
     * @var FormatterInterface[]
     */
    private array $formatters;

    /**
     * @param array $configuration
     * @param array $formatters
     */
    public function open(string $file, array $configuration = [], array $formatters = []): void
    {
        $this->configuration = array_merge(self::DEFAULT, $configuration);
        $this->formatters = $formatters;

        try {
            $fp = file($file);
            $this->count = \count($fp) - 1;

            $this->file = \fopen($file, 'rb');
            if (false === $this->file) {
                throw new \RuntimeException(sprintf('cant\' open "%s" file', $file));
            }
            $this->headers = $this->getCSV();
            foreach ($this->headers as $key => $header) {
                $header = trim($header, "\xEF\xBB\xBF"); // remove BOM from headers
                foreach ($this->formatters as $formatter) {
                    $header = $formatter->format($header);
                }
                $this->headers[$key] = trim($header);
            }
        } catch (\Exception $exception) {
            throw new \RuntimeException(sprintf('cant\' process "%s" file', $file));
        }
    }

    public function read(): \Traversable
    {
        while ($row = $this->getCSV()) {
            foreach ($row as $key => $field) {
                foreach ($this->formatters as $formatter) {
                    $field = $formatter->format($field);
                }
                $row[$key] = trim($field);
            }
            if (count($this->headers) !== count($row)) {
                $message = 'The number of fields is different from the number of headers';

                throw new ReaderException($message);
            }
            yield array_combine($this->headers, $row);
        }
    }

    public function close(): void
    {
        fclose($this->file);
    }

    public function count(): int
    {
        return $this->count;
    }

    public function getIterator(): \Traversable
    {
        return $this->read();
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return array|false|null
     */
    private function getCSV()
    {
        return fgetcsv(
            $this->file,
            0,
            $this->configuration[self::DELIMITER],
            $this->configuration[self::ENCLOSURE],
            $this->configuration[self::ESCAPE]
        );
    }
}
