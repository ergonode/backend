<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Reader;

use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Reader\Infrastructure\Processor\CsvReaderProcessor;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\Transformer\Infrastructure\Provider\ConverterMapperProvider;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Reader\Infrastructure\Provider\ReaderProcessorProvider;

/**
 */
class Magento1CsvReader
{
    /**
     * @var ConverterMapperProvider
     */
    private ConverterMapperProvider $mapper;

    /**
     * @var ReaderProcessorProvider
     */
    private ReaderProcessorProvider $provider;

    /**
     * @var string
     */
    private string $directory;

    /**
     * @param ConverterMapperProvider $mapper
     * @param ReaderProcessorProvider $provider
     * @param string                  $directory
     */
    public function __construct(
        ConverterMapperProvider $mapper,
        ReaderProcessorProvider $provider,
        string $directory
    ) {
        $this->mapper = $mapper;
        $this->provider = $provider;
        $this->directory = $directory;
    }

    /**
     * @param Magento1CsvSource $source
     * @param Import            $import
     * @param Transformer       $transformer
     *
     * @return array
     */
    public function read(Magento1CsvSource $source, Import $import, Transformer $transformer): array
    {
        $filename = \sprintf('%s%s', $this->directory, $import->getFile());
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $fileReader = $this->provider->provide($extension);

        $products = [];
        $sku = null;
        $type = null;

        $configuration = [
            CsvReaderProcessor::DELIMITER => $source->getDelimiter(),
            CsvReaderProcessor::ENCLOSURE => $source->getEnclosure(),
            CsvReaderProcessor::ESCAPE => $source->getEscape(),
        ];

        $fileReader->open($filename, $configuration);

        foreach ($fileReader->read() as $row) {
            if ($row['sku']) {
                $sku = $row['sku'];
                $products[$sku] = [];
            }

            $code = $row['_store'] ?: 'default';
            $row = $this->process($transformer, $row);
            if (!array_key_exists($code, $products[$sku])) {
                $products[$sku][$code] = $row;
            } else {
                foreach ($row as $field => $value) {
                    if ('' !== $value && null !== $value) {
                        if ($products[$sku][$code][$field] !== '') {
                            $products[$sku][$code][$field] .= ','.$value;
                        }
                    }
                }
            }
        }

        return $products;
    }

    /**
     * @param Transformer $transformer
     * @param array       $record
     *
     * @return array
     */
    public function process(Transformer $transformer, array $record): array
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
