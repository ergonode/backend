<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Infrastructure\Reader;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Reader\Infrastructure\Exception\ReaderException;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;

class Magento1CsvReader
{
    private string $directory;

    /**
     * @var mixed
     */
    private $file;

    /**
     * @var array
     */
    private array $headers;


    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

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

    public function close(): void
    {
        fclose($this->file);
    }

    /**
     * @param AbstractAttribute[] $attributes
     *
     * @throws ReaderException
     */
    public function read(array $attributes): ?ProductModel
    {
        $sku = null;
        $type = null;
        $template = null;
        $code = null;
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

            $line = $this->process($attributes, $line);

            if (!array_key_exists($code, $product)) {
                $product[$code] = $line;
            } else {
                foreach ($line as $field => $value) {
                    if ('' !== $value && null !== $value) {
                        if (null === $product[$code][$field] || '' === $product[$code][$field]) {
                            $product[$code][$field] = $value;
                        } else {
                            $product[$code][$field] .= ','.$value;
                        }
                    }
                }
            }
        }

        if (!empty($product)) {
            $result = new ProductModel($sku, $type, $template, $product[null]);

            foreach ($product as $store => $version) {
                if (empty($store)) {
                    continue;
                }
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
     * @param AbstractAttribute[] $attributes
     *
     * @return array
     */
    private function process(array $attributes, array $record): array
    {
        $result = [];

        foreach ($attributes as $code => $attribute) {
            $result[$attribute->getCode()->getValue()] = $record[$code];
        }

        $result['variants'] = $record['_super_products_sku'];
        $result['relations'] = $record['_associated_sku'];
        $result['esa_categories'] = $record['_category'];

        if (array_key_exists('_category_root', $record)) {
            $result['esa_categories'] = sprintf('%s/%s', $record['_category'], $record['_category_root']);
        }

        $result['bindings'] = null;
        if (!empty($record['_super_attribute_code'])
            && array_key_exists($record['_super_attribute_code'], $attributes)) {
            $result['bindings'] = $attributes[$record['_super_attribute_code']]->getCode()->getValue();
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
