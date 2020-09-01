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
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param ImportActionProvider           $importActionProvider
     * @param ImportErrorRepositoryInterface $repository
     * @param CommandBusInterface            $commandBus
     */
    public function __construct(
        ImportActionProvider $importActionProvider,
        ImportErrorRepositoryInterface $repository,
        CommandBusInterface $commandBus
    ) {
        $this->importActionProvider = $importActionProvider;
        $this->repository = $repository;
        $this->commandBus = $commandBus;
    }

    /**
     * @param ProcessImportCommand $command
     */
    public function __invoke(ProcessImportCommand $command)
    {
        $importId = $command->getImportId();
        $step = $command->getSteps()->getPosition();
        $steps = $command->getSteps()->getCount();
        $number = $command->getRecords()->getPosition();
        $numbers = $command->getRecords()->getCount();
        $record = $command->getRecord();

        try {
            $action = $this->importActionProvider->provide($command->getAction());
            Assert::notNull($action, sprintf('Can\'t find action %s', $command->getAction()));

            $action->action($command->getImportId(), $record);

            if ($step === $steps && $number === $numbers) {
                $this->commandBus->dispatch(new EndImportCommand($importId), true);
            }
        } catch (\Throwable $exception) {
            $line = new ImportError($importId, $step, $number, $exception->getMessage());
            $this->repository->save($line);

            throw $exception;
        }
    }
}
