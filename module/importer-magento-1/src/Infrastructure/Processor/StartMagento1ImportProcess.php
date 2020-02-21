<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor;

use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterMagento1\Domain\Entity\Magento1CsvSource;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Webmozart\Assert\Assert;
use Ergonode\ImporterMagento1\Infrastructure\Reader\Magento1CsvReader;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;

/**
 */
class StartMagento1ImportProcess
{
    /**
     * @var SourceRepositoryInterface
     */
    private SourceRepositoryInterface $sourceRepository;

    /**
     * @var TransformerRepositoryInterface
     */
    private TransformerRepositoryInterface $transformerRepository;

    /**
     * @var Magento1CsvReader
     */
    private Magento1CsvReader $reader;

    /**
     * @var Magento1ProcessorStepInterface[]
     */
    private array $steps;

    /**
     * @param SourceRepositoryInterface            $sourceRepository
     * @param TransformerRepositoryInterface       $transformerRepository
     * @param Magento1CsvReader                    $reader
     * @param array|Magento1ProcessorStepInterface ...$steps
     */
    public function __construct(
        SourceRepositoryInterface $sourceRepository,
        TransformerRepositoryInterface $transformerRepository,
        Magento1CsvReader $reader,
        Magento1ProcessorStepInterface ...$steps
    ) {
        $this->sourceRepository = $sourceRepository;
        $this->transformerRepository = $transformerRepository;
        $this->reader = $reader;
        $this->steps = $steps;
    }

    /**
     * @param Import $import
     */
    public function start(Import $import): void
    {
        try {
            /** @var Magento1CsvSource $source */
            $source = $this->sourceRepository->load($import->getSourceId());
            Assert::notNull($source);
            $transformer = $this->transformerRepository->load($import->getTransformerId());
            Assert::notNull($transformer);


            $products = $this->reader->read($source, $import, $transformer);

            $result = [];
            foreach ($products as $sku => $product) {
                $result[$sku] = new ProductModel();
                foreach ($product as $code => $version) {
                    $result[$sku]->add($code, $version);
                }
            }

            foreach ($this->steps as $step) {
                $step->process($import, $result, $transformer, $source);
            }
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}
