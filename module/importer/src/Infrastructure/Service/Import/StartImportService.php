<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Service\Import;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterMagento2\Domain\Entity\Magento2CsvSource;
use Ergonode\Reader\Infrastructure\Provider\ReaderProcessorProvider;
use Ergonode\Reader\Infrastructure\ReaderProcessorInterface;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Infrastructure\Action\CategoryImportAction;
use Ergonode\Transformer\Infrastructure\Action\ProductImportAction;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Webmozart\Assert\Assert;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Transformer\Infrastructure\Provider\ConverterMapperProvider;

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
     * @var string
     */
    private string $directory;

    /**
     * @param SourceRepositoryInterface      $sourceRepository
     * @param TransformerRepositoryInterface $transformerRepository
     * @param ReaderProcessorProvider        $provider
     * @param ConverterMapperProvider        $mapperProvider
     * @param CommandBusInterface            $commandBus
     * @param string                         $directory
     */
    public function __construct(
        SourceRepositoryInterface $sourceRepository,
        TransformerRepositoryInterface $transformerRepository,
        ReaderProcessorProvider $provider,
        ConverterMapperProvider $mapperProvider,
        CommandBusInterface $commandBus,
        string $directory
    ) {
        $this->sourceRepository = $sourceRepository;
        $this->transformerRepository = $transformerRepository;
        $this->provider = $provider;
        $this->mapperProvider = $mapperProvider;
        $this->commandBus = $commandBus;
        $this->directory = $directory;
    }

    /**
     * @param Import $import
     *
     * @throws \ReflectionException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function start(Import $import): void
    {
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

        $groups = $this->getGroupedSku($rows);
        $categories = $this->getCategories($groups);

        $i = 0;
        foreach ($groups as $type => $products) {
            foreach ($products as $sku => $product) {
                $i++;
                $row = $this->merge($product);
                $result[$type][$sku] = $this->transform($row, $transformer);
            }
        }

        foreach ($categories as $category) {
            $record = new Record();
            $record->set('code', new StringValue($category['code']));
            $record->set('name', new TranslatableString(['PL' => $category['name']]))
            $command = new ProcessImportCommand($import->getId(), $i, reset($version), CategoryImportAction::TYPE);
            $this->commandBus->dispatch($command);
        }

        $i = 0;
        foreach ($groups as $type => $products) {
            foreach ($products as $sku => $product) {
                $i++;
//            $command = new ProcessImportCommand($import->getId(), $i, reset($version), ProductImportAction::TYPE);
//            $this->commandBus->dispatch($command);
            }
        }

        $end = microtime(true);

        var_dump($i);
        echo($end - $start);
    }

    /**
     * @param array $rows
     *
     * @return array
     */
    private function getGroupedSku(array $rows): array
    {
        $products['simple'] = [];
        $products['configurable'] = [];
        $products['bundle'] = [];
        $sku = null;
        $type = null;


        foreach ($rows as $row) {
            if (!empty($row['sku'])) {
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
     * @param array $rows
     *
     * @return array
     */
    public function getCategories(array $rows): array
    {
        $result = [];
        foreach ($rows as $row) {
            if($row['sku'] && $row('_category')) {
                $categories = implode('\\', $row['_category']);
                foreach ($categories as $category) {
                    $result[$category]['code'] = $category;
                    $language = $row['_store']?:Language::EN;
                    $result[$category]['name'][$language] = $category;
                }
            }
        }

        return $result;
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
                $record->add($collection, $field, $value);
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
                        $product[$key] .= ',' . $value;
                    }
                }
            }
        }

        return $product;
    }
}
