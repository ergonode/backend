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
use Ergonode\Importer\Domain\Repository\ImportLineRepositoryInterface;
use Ergonode\Transformer\Infrastructure\Provider\ImportActionProvider;
use Ergonode\Importer\Domain\Entity\ImportLine;
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
     * @var ImportLineRepositoryInterface
     */
    private ImportLineRepositoryInterface $repository;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param ImportActionProvider          $importActionProvider
     * @param ImportLineRepositoryInterface $repository
     * @param CommandBusInterface           $commandBus
     */
    public function __construct(
        ImportActionProvider $importActionProvider,
        ImportLineRepositoryInterface $repository,
        CommandBusInterface $commandBus
    ) {
        $this->importActionProvider = $importActionProvider;
        $this->repository = $repository;
        $this->commandBus = $commandBus;
    }

    /**
     * @param ProcessImportCommand $command
     *
     * @throws DBALException
     */
    public function __invoke(ProcessImportCommand $command)
    {
        $importId = $command->getImportId();
        $lineNumber = $command->getRecords()->getPosition();
        $record = $command->getRecord();

        $line = new ImportLine($importId, $lineNumber, '{}');

        try {
            $action = $this->importActionProvider->provide($command->getAction());

            if (!$action) {
                throw new \RuntimeException(sprintf('Can\'t find action %s', $command->getAction()));
            }

            $action->action($command->getImportId(), $record);

            $records = $command->getRecords();
            $steps = $command->getSteps();

            if ($steps->getCount() === $steps->getPosition() && $records->getCount() === $records->getPosition()) {
                $this->commandBus->dispatch(new EndImportCommand($importId));
            }
        } catch (\Throwable $exception) {
            $line->addError($exception->getMessage());
        }

        $this->repository->save($line);
    }
}
