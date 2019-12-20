<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Service;

use Ergonode\Importer\Domain\Command\CreateImportLineCommand;
use Ergonode\Importer\Domain\Command\EndProcessImportCommand;
use Ergonode\Importer\Domain\Command\StartProcessImportCommand;
use Ergonode\Importer\Domain\Command\StopProcessImportCommand;
use Ergonode\Importer\Domain\Entity\AbstractImport;
use Ergonode\Importer\Domain\Entity\FileImport;
use Ergonode\Importer\Domain\Entity\ImportLineId;
use Ergonode\Reader\Domain\Repository\ReaderRepositoryInterface;
use Ergonode\Reader\Infrastructure\Provider\ReaderProcessorProvider;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;

/**
 */
class ImportService
{
    /**
     * @var ReaderProcessorProvider
     */
    private $provider;

    /**
     * @var ReaderRepositoryInterface
     */
    private $repository;

    /**
     * @var MessageBusInterface
     */
    private $commandBus;

    /**
     * @var string
     */
    private $directory;

    /**
     * @param ReaderProcessorProvider   $provider
     * @param ReaderRepositoryInterface $repository
     * @param MessageBusInterface       $commandBus
     * @param string                    $directory
     */
    public function __construct(
        ReaderProcessorProvider $provider,
        ReaderRepositoryInterface $repository,
        MessageBusInterface $commandBus,
        string $directory
    ) {
        $this->provider = $provider;
        $this->repository = $repository;
        $this->commandBus = $commandBus;
        $this->directory = $directory;
    }

    /**
     * @param AbstractImport|FileImport $import
     * @param TransformerId|null        $transformerId
     * @param string                    $action
     */
    public function import(AbstractImport $import, TransformerId $transformerId = null, string $action = null): void
    {
        try {
            $options = $import->getOptions();
            $filename = \sprintf('%s%s', $this->directory, $options['file']);
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $fileReader = $this->provider->getReader($extension);
            $reader = $this->repository->load($import->getReaderId());
            Assert::notNull($reader, sprintf('Can\'t find reader %s', $import->getReaderId()->getValue()));

            $fileReader->open($filename, $reader->getConfiguration(), $reader->getFormatters());
            $this->commandBus->dispatch(new StartProcessImportCommand($import->getId()));
            foreach ($fileReader->read() as $key => $row) {
                $command = new CreateImportLineCommand(ImportLineId::generate(), $import->getId(), $row);
                $this->commandBus->dispatch($command);
            }
            $fileReader->close();
            $this->commandBus->dispatch(new EndProcessImportCommand($import->getId(), $transformerId, $action));
        } catch (\Exception $e) {
            $this->commandBus->dispatch(new StopProcessImportCommand($import->getId(), $e->getMessage()));
        }
    }
}
