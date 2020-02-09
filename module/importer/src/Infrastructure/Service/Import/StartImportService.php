<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Service\Import;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterMagento2\Domain\Entity\Magento2CsvSource;
use Ergonode\Reader\Infrastructure\Provider\ReaderProcessorProvider;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Infrastructure\Action\CategoryImportAction;
use Ergonode\Transformer\Infrastructure\Action\ProductImportAction;
use Webmozart\Assert\Assert;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Transformer\Infrastructure\Provider\ConverterMapperProvider;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1CategoryProcessor;
use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\ImporterMagento1\Infrastructure\Processor\Magento1AttributeProcessor;
use Ergonode\Transformer\Infrastructure\Action\AttributeImportAction;

/**
 */
class StartImportService
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
     * @var ConverterMapperProvider
     */
    private ConverterMapperProvider $mapperProvider;


    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var Magento1CategoryProcessor
     */
    private Magento1CategoryProcessor $categoryProcessor;

    /**
     * @var Magento1AttributeProcessor
     */
    private Magento1AttributeProcessor $attributeProcessor;

    /**
     * @var string
     */
    private string $directory;

    /**
     * @param SourceRepositoryInterface $sourceRepository
     * @param TransformerRepositoryInterface $transformerRepository
     * @param ReaderProcessorProvider $provider
     * @param ConverterMapperProvider $mapperProvider
     * @param CommandBusInterface $commandBus
     * @param Magento1CategoryProcessor $categoryProcessor
     * @param Magento1AttributeProcessor $attributeProcessor
     * @param string $directory
     */
    public function __construct(
        SourceRepositoryInterface $sourceRepository,
        TransformerRepositoryInterface $transformerRepository,
        ReaderProcessorProvider $provider,
        ConverterMapperProvider $mapperProvider,
        CommandBusInterface $commandBus,
        Magento1CategoryProcessor $categoryProcessor,
        Magento1AttributeProcessor $attributeProcessor,
        string $directory
    ) {
        $this->sourceRepository = $sourceRepository;
        $this->transformerRepository = $transformerRepository;
        $this->provider = $provider;
        $this->mapperProvider = $mapperProvider;
        $this->commandBus = $commandBus;
        $this->categoryProcessor = $categoryProcessor;
        $this->attributeProcessor = $attributeProcessor;
        $this->directory = $directory;
    }

    /**
     * @param Import $import
     */
    public function start(Import $import): void
    {
        $language = new Language(Language::EN);
        $importId = $import->getId();

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

            $rows = [];
            $fileReader->open($filename, $source->getConfiguration());
            foreach ($fileReader->read() as $row) {
                $rows[] = $row;
            }

            $this->processAttributes($importId, $rows, $language);
            $this->processCategories($importId, $rows, $language);
            $this->processProducts($importId, $rows, $transformer);

            $end = microtime(true);

            echo ($end - $start).PHP_EOL;
        } catch (\Throwable $exception) {
            echo $exception->getMessage().PHP_EOL;
            echo print_r($exception->getTraceAsString(), true);
            die;
        }
    }

    /**
     * @param ImportId $id
     * @param array    $rows
     * @param Language $language
     */
    private function processCategories(ImportId $id, array $rows, Language $language): void
    {
        $categories = $this->categoryProcessor->process($rows, $language);
        $i = 0;
        foreach ($categories as $category) {
            $i++;
            $command = new ProcessImportCommand($id, $i, $category, CategoryImportAction::TYPE);
            $this->commandBus->dispatch($command);
        }
    }

    /**
     * @param ImportId $id
     * @param array    $rows
     * @param Language $language
     *
     * @throws \Exception
     */
    private function processAttributes(ImportId $id, array $rows, Language $language): void
    {
        $attributes = $this->attributeProcessor->process($rows, $language);
        $i = 0;
        foreach ($attributes as $attribute) {
            $i++;
            $command = new ProcessImportCommand($id, $i, $attribute, AttributeImportAction::TYPE);
            $this->commandBus->dispatch($command);
        }
    }

    /**
     * @param ImportId    $id
     * @param array       $rows
     * @param Transformer $transformer
     */
    private function processProducts(ImportId $id, array $rows, Transformer $transformer): void
    {
        $i = 0;
        $products = $this->getGroupedProducts($rows);
        foreach ($products['simple'] as $key => $product) {
            $product = reset($product);
            $i++;
            $record = $this->transform($product, $transformer);
            $command = new ProcessImportCommand($id, $i, $record, ProductImportAction::TYPE);
            $this->commandBus->dispatch($command);
        }
    }

    /**
     * @param array $rows
     *
     * @return array
     */
    private function getGroupedProducts(array $rows): array
    {
        $products['simple'] = [];
        $products['configurable'] = [];
        $products['bundle'] = [];
        $sku = null;
        $type = null;

        foreach ($rows as $row) {
            if ($row['sku'] !== null && $row['sku'] !== '') {
                $sku = $row['sku'];
                $type = $row['_type'];
            }

            if ($type && $sku) {
                $products[$type][$sku][] = $row;
            }
        }

        return $products;
    }

    /**
     * @param array       $row
     * @param Transformer $transformer
     *
     * @return Record
     */
    public function transform(array $row, Transformer $transformer): Record
    {
        $record = new Record();
        foreach ($transformer->getConverters() as $collection => $converters) {
            /** @var ConverterInterface $converter */
            foreach ($converters as $field => $converter) {
                $mapper = $this->mapperProvider->provide($converter);
                $value = $mapper->map($converter, $row);
                if ($collection === 'values') {
                    $record->setValue($field, $value);
                } else {
                    $record->set($field, $value);
                }
            }
        }

        return $record;
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
                        $product[$key] .= ','.$value;
                    }
                }
            }
        }

        return $product;
    }
}
