<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\ImporterMagento1\Infrastructure\Reader\Magento1CsvReader;
use Ergonode\Reader\Infrastructure\Exception\ReaderException;

/**
 */
class Magento1ProductsProvider
{
    /**
     * @var Magento1CsvReader
     */
    private Magento1CsvReader $reader;

    /**
     * @param Magento1CsvReader $reader
     */
    public function __construct(Magento1CsvReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param Import      $import
     * @param Transformer $transformer
     *
     * @return array
     *
     * @throws ReaderException
     */
    public function getProducts(Import $import, Transformer $transformer): array
    {
        $result = [];
        $this->reader->open($import);

        while ($product = $this->reader->read($transformer)) {
            $result[] = $product;
        }

        $this->reader->close();

        return $result;
    }
}