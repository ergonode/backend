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
use Ergonode\Importer\Domain\Entity\ImportLine;
use Ergonode\Importer\Domain\Repository\ImportLineRepositoryInterface;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\ImporterMagento2\Domain\Entity\Magento2CsvSource;
use Ergonode\Reader\Infrastructure\Provider\ReaderProcessorProvider;
use Ergonode\Transformer\Infrastructure\Action\ProductImportAction;
use Webmozart\Assert\Assert;

/**
 */
class StartImportService
{
    /**
     * @var SourceRepositoryInterface
     */
    private SourceRepositoryInterface $sourceRepository;

    /**
     * @var ImportLineRepositoryInterface
     */
    private ImportLineRepositoryInterface $lineRepository;

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
     * @param SourceRepositoryInterface     $sourceRepository
     * @param ImportLineRepositoryInterface $lineRepository
     * @param ReaderProcessorProvider       $provider
     * @param CommandBusInterface           $commandBus
     * @param string                        $directory
     */
    public function __construct(
        SourceRepositoryInterface $sourceRepository,
        ImportLineRepositoryInterface $lineRepository,
        ReaderProcessorProvider $provider,
        CommandBusInterface $commandBus,
        string $directory
    ) {
        $this->sourceRepository = $sourceRepository;
        $this->lineRepository = $lineRepository;
        $this->provider = $provider;
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

        $file = $source->getFile();
        $filename = \sprintf('%s%s', $this->directory, $file);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $fileReader = $this->provider->provide($extension);

        $fileReader->open($filename, $source->getConfiguration());

        $i = 0;
        foreach ($fileReader->read() as $key => $row) {
            $i++;
            $line = new ImportLine($import->getId(), $i, json_encode($row, JSON_THROW_ON_ERROR, 512));

            $this->lineRepository->save($line);
            $command = new ProcessImportCommand($import->getId(), $i, $row, ProductImportAction::TYPE);
            $this->commandBus->dispatch($command);
        }

        $fileReader->close();
    }
}
