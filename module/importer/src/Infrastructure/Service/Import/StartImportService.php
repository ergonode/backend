<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Service\Import;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Importer\Domain\Command\Import\StopImportCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Entity\ImportLine;
use Ergonode\Importer\Domain\Repository\ImportLineRepositoryInterface;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterMagento2\Domain\Entity\Source\Magento2CsvSource;
use Ergonode\Reader\Domain\Repository\ReaderRepositoryInterface;
use Ergonode\Reader\Infrastructure\Provider\ReaderProcessorProvider;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class StartImportService
{
    /**
     * @var TransformerRepositoryInterface
     */
    private TransformerRepositoryInterface $transformerRepository;

    /**
     * @var SourceRepositoryInterface
     */
    private SourceRepositoryInterface $sourceRepository;

    /**
     * @var ImportLineRepositoryInterface
     */
    private ImportLineRepositoryInterface $lineRepository;

    /**
     * @var ReaderRepositoryInterface
     */
    private ReaderRepositoryInterface $readerRepository;

    /**
     * @var ReaderProcessorProvider
     */
    private ReaderProcessorProvider $provider;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var string
     */
    private string $directory;

    /**
     * @param TransformerRepositoryInterface $transformerRepository
     * @param SourceRepositoryInterface      $sourceRepository
     * @param ImportLineRepositoryInterface  $lineRepository
     * @param ReaderRepositoryInterface      $readerRepository
     * @param ReaderProcessorProvider        $provider
     * @param CommandBusInterface            $commandBus
     * @param string                         $directory
     */
    public function __construct(
        TransformerRepositoryInterface $transformerRepository,
        SourceRepositoryInterface $sourceRepository,
        ImportLineRepositoryInterface $lineRepository,
        ReaderRepositoryInterface $readerRepository,
        ReaderProcessorProvider $provider,
        CommandBusInterface $commandBus,
        string $directory
    ) {
        $this->transformerRepository = $transformerRepository;
        $this->sourceRepository = $sourceRepository;
        $this->lineRepository = $lineRepository;
        $this->readerRepository = $readerRepository;
        $this->provider = $provider;
        $this->commandBus = $commandBus;
        $this->directory = $directory;
    }

    /**
     * @param Import $import
     */
    public function start(Import $import): void
    {
        /** @var Magento2CsvSource $source */
        $source = $this->sourceRepository->load($import->getSourceId());
        Assert::notNull($source);

        try {
            $file = $source->getFile();
            $filename = \sprintf('%s%s', $this->directory, $file);
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $fileReader = $this->provider->getReader($extension);

            $fileReader->open($filename, $source->getConfiguration());

            $i = 0;
            foreach ($fileReader->read() as $key => $row) {
                $i++;
                $line = new ImportLine($import->getId(), $i, $row);
                $this->lineRepository->save($line);
                $this->commandBus->dispatch(new ProcessImportCommand($transformer->getId(), $row, $processor->getAction()));
            }
            $fileReader->close();
        } catch (\Exception $e) {
            $this->commandBus->dispatch(new StopImportCommand($import->getId(), $e->getMessage()));
        }
    }
}
