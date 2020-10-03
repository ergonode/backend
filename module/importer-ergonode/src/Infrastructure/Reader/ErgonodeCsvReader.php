<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Infrastructure\Reader;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode\Infrastructure\Model\ProductModel;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Reader\Infrastructure\Exception\ReaderException;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Transformer\Infrastructure\Provider\ConverterMapperProvider;
use League\Csv\Reader;

/**
 */
final class ErgonodeCsvReader
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
     * @var Reader
     */
    private Reader $reader;

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
     *
     * @throws ReaderFileProcessException
     */
    public function open(Import $import): void
    {
        $file = "{$this->directory}{$import->getFile()}";

        try {
            $this->reader = Reader::createFromPath($file);
            $this->reader->setHeaderOffset(0);
            $this->reader->skipEmptyRecords();
            $this->reader->skipInputBOM();
        } catch (\Exception $exception) {
            throw new ReaderFileProcessException($file, $exception);
        }
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

        foreach ($this->reader as $offset => $line) {
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
}
