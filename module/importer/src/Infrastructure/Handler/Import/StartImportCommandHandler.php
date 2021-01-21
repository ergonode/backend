<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler\Import;

use Ergonode\Importer\Domain\Command\Import\StartImportCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Importer\Infrastructure\Provider\ImportProcessorProvider;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Domain\Command\Import\StopImportCommand;
use Ergonode\Importer\Domain\Command\Import\EndImportCommand;
use Psr\Log\LoggerInterface;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Reader\Infrastructure\Exception\ReaderException;

class StartImportCommandHandler
{
    private ImportRepositoryInterface $importRepository;

    private SourceRepositoryInterface $sourceRepository;

    private ImportProcessorProvider $provider;

    private CommandBusInterface $commandBus;

    private LoggerInterface $logger;

    public function __construct(
        ImportRepositoryInterface $importRepository,
        SourceRepositoryInterface $sourceRepository,
        ImportProcessorProvider $provider,
        CommandBusInterface $commandBus,
        LoggerInterface $logger
    ) {
        $this->importRepository = $importRepository;
        $this->sourceRepository = $sourceRepository;
        $this->provider = $provider;
        $this->commandBus = $commandBus;
        $this->logger = $logger;
    }

    /**
     * @throws \ReflectionException
     */
    public function __invoke(StartImportCommand $command): void
    {
        $message = null;
        $import = $this->importRepository->load($command->getId());
        Assert::notNull($import);
        $source = $this->sourceRepository->load($import->getSourceId());
        Assert::notNull($source);

        $import->start();
        $this->importRepository->save($import);

        $processor = $this->provider->provide($source->getType());

        try {
            $processor->start($import);
            $this->importRepository->save($import);
        } catch (ImportException|ReaderException $exception) {
            $message = $exception->getMessage();
        } catch (\Throwable $exception) {
            $this->logger->error($exception);
            $message = 'Import processing error';

            die($exception->getMessage());
        }

        if ($message) {
            $this->commandBus->dispatch(new StopImportCommand($import->getId(), $message), true);
        } else {
            $this->commandBus->dispatch(new EndImportCommand($import->getId()), true);
        }
    }
}
