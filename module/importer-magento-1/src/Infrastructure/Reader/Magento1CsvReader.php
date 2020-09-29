<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Reader;

use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Transformer\Infrastructure\Provider\ConverterMapperProvider;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Reader\Infrastructure\Exception\ReaderException;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\Product\Domain\ValueObject\Sku;

/**
 */
class Magento1CsvReader
{
    /**
     * @var ConverterMapperProvider
     */
    private ConverterMapperProvider $mapper;

    /**
     * @var string
     */
    private string $directory;

    /**
     * @var mixed
     */
    private $file;

    /**
     * @var array
     */
    private array $headers;


    /**
     * @param ConverterMapperProvider $mapper
     * @param string                  $directory
     */
    public function __construct(ConverterMapperProvider $mapper, string $directory)
    {
        $this->mapper = $mapper;
        $this->directory = $directory;
    }

    /**
     * @param Import $import
     */
    public function open(Import $import): void
    {
        $file = \sprintf('%s%s', $this->directory, $import->getFile());

        try {
            $this->file = \fopen($file, 'rb');
            if (false === $this->file) {
                throw new \RuntimeException(sprintf('cant\' open "%s" file', $file));
            }
            $this->headers = $this->getCSV();
            foreach ($this->headers as $key => $header) {
                $header = trim($header, "\xEF\xBB\xBF"); // remove BOM from headers

                $this->headers[$key] = trim($header);
            }
        } catch (\Exception $exception) {
            throw new \RuntimeException(sprintf('cant\' process "%s" file', $file));
        }
    }

    /**
     */
    public function close(): void
    {
        fclose($this->file);
    }

    /**
     * @param Transformer $transformer
     *
     * @return ProductModel|null
     *
     * @throws ReaderException
     */
    public function read(Transformer $transformer): ?ProductModel
    {
        $sku = null;
        $type = null;
        $template = null;
        $code = 'default';
        $product = [];

        $lines = $this->getLines();
        foreach ($lines as $line) {
            if (!empty($line['_store']) && $code !== $line['_store']) {
                $code = $line['_store'];
            }

            if (null === $sku) {
                $sku = $line['sku'];
                $type = $line['_type'];
                $template = $line['_attribute_set'];
            }

            $line = $this->process($transformer, $line);

            if (!array_key_exists($code, $product)) {
                $product[$code] = $line;
            } else {
                foreach ($line as $field => $value) {
                    if ('' !== $value && null !== $value) {
                        if ($product[$code][$field] !== '') {
                            if ('default' === $code) {
                                $product[$code][$field] = $value;
                            } else {
                                $product[$code][$field] .= ','.$value;
                            }
                        } else {
                            $product[$code][$field] = $value;
                        }
                    }
                }
            }
        }

        if (!empty($product)) {
            $result = new ProductModel(new Sku($sku), $type, $template);

            foreach ($product as $store => $version) {
                $result->set($store, $version);
            }

            return $result;
        }

        return null;
    }

    /**
     * @return array
     *
     * @throws ReaderException
     */
    private function getLines(): array
    {
        $lines = [];
        $line = $this->readLine();
        if ($line) {
            $lines[] = $line;
            $offset = ftell($this->file);
            if (!empty($lines)) {
                while ($line = $this->readLine()) {
                    if ($line['sku'] !== '') {
                        fseek($this->file, $offset);
                        break;
                    }
                    $offset = ftell($this->file);
                    $lines[] = $line;
                }
            }
        }

        return $lines;
    }

    /**
     * @return array|null
     *
     * @throws ReaderException
     */
    private function readLine(): ?array
    {
        $row = $this->getCSV();
        if ($row) {
            foreach ($row as $key => $field) {
                $row[$key] = trim($field);
            }
            if (count($this->headers) !== count($row)) {
                $message = 'The number of fields is different from the number of headers';

                throw new ReaderException($message);
            }

            return array_combine($this->headers, $row);
        }

        return null;
    }

    /**
     * @param Transformer $transformer
     * @param array       $record
     *
     * @return array
     */
    private function process(Transformer $transformer, array $record): array
    {
        $result = [];

        foreach ($transformer->getAttributes() as $field => $converter) {
            /** @var ConverterInterface $converter */
            $mapper = $this->mapper->provide($converter);
            $value = $mapper->map($converter, $record);
            $result[$field] = $value;
        }

        foreach ($transformer->getFields() as $field => $converter) {
            /** @var ConverterInterface $converter */
            $mapper = $this->mapper->provide($converter);
            $value = $mapper->map($converter, $record);
            $result[$field] = $value;
        }

        return $result;
    }

    /**
     * @return array|false|null
     */
    private function getCSV()
    {
        return fgetcsv($this->file);
    }
}
