<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Import;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ErrorImportCommand;
use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\Importer\Domain\Repository\ImportLineRepositoryInterface;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Domain\Repository\SourceRepositoryInterface;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Ergonode\Transformer\Infrastructure\Process\TransformProcess;
use Ergonode\Transformer\Infrastructure\Provider\ImportActionProvider;
use Webmozart\Assert\Assert;

/**
 */
class ProcessImportCommandHandler
{
    /**
     * @var ImportRepositoryInterface
     */
    private ImportRepositoryInterface $importerRepository;

    /**
     * @var TransformerRepositoryInterface
     */
    private TransformerRepositoryInterface $transformerRepository;

    /**
     * @var TransformProcess
     */
    private TransformProcess $transformationProcess;

    /**
     * @var ImportActionProvider
     */
    private ImportActionProvider $importActionProvider;

    /**
     * @var ImportLineRepositoryInterface
     */
    private ImportLineRepositoryInterface $repository;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param ImportRepositoryInterface $importerRepository
     * @param TransformerRepositoryInterface $transformerRepository
     * @param TransformProcess $transformationProcess
     * @param ImportActionProvider $importActionProvider
     * @param ImportLineRepositoryInterface $repository
     * @param CommandBusInterface $commandBus
     */
    public function __construct(
        ImportRepositoryInterface $importerRepository,
        TransformerRepositoryInterface $transformerRepository,
        TransformProcess $transformationProcess,
        ImportActionProvider $importActionProvider,
        ImportLineRepositoryInterface $repository,
        CommandBusInterface $commandBus
    ) {
        $this->importerRepository = $importerRepository;
        $this->transformerRepository = $transformerRepository;
        $this->transformationProcess = $transformationProcess;
        $this->importActionProvider = $importActionProvider;
        $this->repository = $repository;
        $this->commandBus = $commandBus;
    }

    /**
     * @param ProcessImportCommand $command
     *
     * @throws \Throwable
     */
    public function __invoke(ProcessImportCommand $command)
    {
        $importId = $command->getImportId();
        $lineNumber = $command->getLine();
        $content = $command->getRow();

        $line = $this->repository->load($importId, $lineNumber);
        Assert::notNull($line);

        try {
            $import = $this->importerRepository->load($command->getImportId());
            Assert::isInstanceOf($import, Import::class);
            $transformer = $this->transformerRepository->load($import->getTransformerId());

            $action = $this->importActionProvider->provide($command->getAction());

            if (!$transformer) {
                throw new \RuntimeException(sprintf('Can\'t find transformer %s', $import->getTransformerId()));
            }

            if (!$action) {
                throw new \RuntimeException(sprintf('Can\'t find action %s', $command->getAction()));
            }

            if ($content) {
                $this->transformationProcess->process($transformer, $action, $content);
            }

        } catch (\Throwable $exception) {
            $this->commandBus->dispatch(new ErrorImportCommand($importId, $lineNumber, $exception->getMessage()));

            throw $exception;
        }
    }
}
