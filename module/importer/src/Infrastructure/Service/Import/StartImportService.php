<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Service\Import;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterMagento2\Domain\Entity\Magento2CsvSource;
use Ergonode\Reader\Infrastructure\Provider\ReaderProcessorProvider;
use Ergonode\Transformer\Infrastructure\Action\ProductImportAction;
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
     * StartImportService constructor.
     *
     * @param SourceRepositoryInterface $sourceRepository
     * @param TransformerRepositoryInterface $transformerRepository
     * @param ReaderProcessorProvider $provider
     * @param ConverterMapperProvider $mapperProvider
     * @param CommandBusInterface $commandBus
     * @param string $directory
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
        /** @var Magento2CsvSource $source */
        $source = $this->sourceRepository->load($import->getSourceId());
        Assert::notNull($source);

        $transformer = $this->transformerRepository->load($import->getTransformerId());

        Assert::notNull($transformer);

        $file = $source->getFile();
        $filename = \sprintf('%s%s', $this->directory, $file);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $fileReader = $this->provider->provide($extension);

        $fileReader->open($filename, $source->getConfiguration());

        $i = 0;
        $sku = null;
        $products = [];
        foreach ($fileReader->read() as $key => $row) {
            if (!empty($row['sku'])) {
                $sku = $row['sku'];
            }

            $result = new Record();
            foreach ($transformer->getConverters() as $collection => $converters) {
                /** @var ConverterInterface $converter */
                foreach ($converters as $field => $converter) {
                    $mapper = $this->mapperProvider->provide($converter);
                    $value = $mapper->map($converter, $row);
                    $result->add($collection, $field, $value);
                }
            }

            $products[$sku][] = $row;
        }

        foreach ($products as $sku => $version) {
            $i++;
            $command = new ProcessImportCommand($import->getId(), $i, reset($version), ProductImportAction::TYPE);
            $this->commandBus->dispatch($command);
        }

        $fileReader->close();
    }
}
