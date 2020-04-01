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
use Doctrine\DBAL\DBALException;
use Webmozart\Assert\Assert;

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
        $step = $command->getSteps()->getPosition();
        $steps = $command->getSteps()->getCount();
        $number = $command->getRecords()->getPosition();
        $numbers = $command->getRecords()->getCount();
        $action = $command->getAction();
        $record = $command->getRecord();

        $line = $this->repository->load($command->getImportId(), $step, $number);

        Assert::notNull($line, sprintf('Can\'t import line %s, step %s, import %s', $step, $number, $action));

        if (!$line->isProcessed()) {
            try {
                $action = $this->importActionProvider->provide($command->getAction());
                Assert::notNull($action, sprintf('Can\'t find action %s', $command->getAction()));

                $action->action($command->getImportId(), $record);
                $line->process();

                if ($step === $steps && $number === $numbers) {
                    $this->commandBus->dispatch(new EndImportCommand($importId), true);
                }
            } catch (\Throwable $exception) {
                $line->addError($exception->getMessage());
            }

            $this->repository->save($line);
        }
    }
}
