<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Infrastructure\Processor;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterMagento1\Infrastructure\Model\ProductModel;
use Ergonode\ImporterMagento2\Domain\Entity\Magento2CsvSource;
use Ergonode\Reader\Infrastructure\Provider\ReaderProcessorProvider;
use Webmozart\Assert\Assert;
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
     * @var ReaderProcessorProvider
     */
    private ReaderProcessorProvider $provider;

    /**
     * @var string
     */
    private string $directory;

    /**
     * @var Magento1ProcessorStepInterface[]
     */
    private array $steps;

    /**
     * @param SourceRepositoryInterface      $sourceRepository
     * @param TransformerRepositoryInterface $transformerRepository
     * @param ReaderProcessorProvider        $provider
     * @param string                         $directory
     * @param Magento1ProcessorStepInterface ...$steps
     */
    public function __construct(
        SourceRepositoryInterface $sourceRepository,
        TransformerRepositoryInterface $transformerRepository,
        ReaderProcessorProvider $provider,
        string $directory,
        Magento1ProcessorStepInterface ...$steps
    ) {
        Assert::allIsInstanceOf($steps, Magento1ProcessorStepInterface::class);

        $this->sourceRepository = $sourceRepository;
        $this->transformerRepository = $transformerRepository;
        $this->provider = $provider;
        $this->directory = $directory;
        $this->steps = $steps;
    }

    /**
     * @param Import $import
     */
    public function start(Import $import): void
    {
        $defaultLanguage = new Language(Language::EN);

        try {
            $start = microtime(true);
            /** @var Magento2CsvSource $source */
            $source = $this->sourceRepository->load($import->getSourceId());
            Assert::notNull($source);

            $transformer = $this->transformerRepository->load($import->getTransformerId());

            Assert::notNull($transformer);

            $file = $source->getFile();
            $filename = \sprintf('%s%s', $this->directory, $file);
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $fileReader = $this->provider->provide($extension);

            $products = [];
            $sku = null;
            $type = null;
            $fileReader->open($filename, $source->getConfiguration());
            foreach ($fileReader->read() as $row) {
                if($row['sku']) {
                    $sku = $row['sku'];
                    $products[$sku] = [];
                }

                $code = $row['_store'] ? $row['sku'] : 'default';
                $row = $this->map($row);
                if(!array_key_exists($code, $products[$sku])) {
                    $products[$sku][$code] = $row;
                } else {
                    foreach ($row as $field => $value) {
                        if($value !== '') {
                            if($products[$sku][$code][$field] !== '') {
                                $products[$sku][$code][$field] .= ',' . $value;
                            }
                        }
                    }
                }
            }


            $result = [];
            foreach ($products as $sku => $product)
            {
                $result[$sku] = new ProductModel();
                foreach ($product as $code => $version) {
                    $result[$sku]->add($code, $version);
                }
            }

            foreach ($this->steps as $step) {
                $step->process($import, $result, $defaultLanguage);
            }

            $end = microtime(true);

            echo ($end - $start) . PHP_EOL;
        } catch (\Throwable $exception) {
            echo $exception->getMessage() . PHP_EOL;
            echo print_r($exception->getTraceAsString(), true);
            die;
        }
    }

    /**
     * @param array $versions
     *
     * @return array
     */
    public function merge(array $versions): array
    {
        $product = [];
        foreach ($versions as $version) {
            foreach ($version as $key => $value) {
                if (null !== $value[$key]) {
                    if (!array_key_exists($key, $product)) {
                        $product[$key] = $value;
                    } else {
                        $product[$key] .= ',' . $value;
                    }
                }
            }
        }

        return $product;
    }

    private function map(array $product): array
    {
        $product['categories'] = $product['_root_category'];
        if($product['categories'] !== '' && $product['_category'] !== '') {
            $product['categories'] .= '/' . $product['_category'];
        }

        unset($product['_root_category'], $product['_category']);

        return $product;
    }
}
