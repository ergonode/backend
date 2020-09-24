<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Import;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\EndImportCommand;
use Ergonode\Importer\Domain\Command\Import\ProcessImportCommand;
use Ergonode\Importer\Domain\Repository\ImportErrorRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Importer\Infrastructure\Provider\ImportActionProvider;
use Ergonode\Importer\Domain\Entity\ImportError;
use Doctrine\DBAL\DBALException;

/**
 */
class ProcessImportCommandHandler
{
    /**
     * @var ImportActionProvider
     */
    private ImportActionProvider $importActionProvider;

    /**
     * @var ImportErrorRepositoryInterface
     */
    private ImportErrorRepositoryInterface $repository;

    /**
     * @param ImportActionProvider           $importActionProvider
     * @param ImportErrorRepositoryInterface $repository
     */
    public function __construct(
        ImportActionProvider $importActionProvider,
        ImportErrorRepositoryInterface $repository
    ) {
        $this->importActionProvider = $importActionProvider;
        $this->repository = $repository;
    }

    /**
     * @param ProcessImportCommand $command
     *
     * @throws \Throwable
     */
    public function __invoke(ProcessImportCommand $command)
    {
        $importId = $command->getImportId();
        $record = $command->getRecord();

        try {
            $action = $this->importActionProvider->provide($command->getAction());
            Assert::notNull($action, sprintf('Can\'t find action %s', $command->getAction()));
            $action->action($command->getImportId(), $record);
        } catch (\Throwable $exception) {
            $line = new ImportError($importId, $exception->getMessage());
            $this->repository->add($line);

            throw $exception;
        }
    }
}
