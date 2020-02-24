<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Import;

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
     * @param ImportActionProvider          $importActionProvider
     * @param ImportLineRepositoryInterface $repository
     */
    public function __construct(
        ImportActionProvider $importActionProvider,
        ImportLineRepositoryInterface $repository
    ) {
        $this->importActionProvider = $importActionProvider;
        $this->repository = $repository;
    }

    /**
     * @param ProcessImportCommand $command
     *
     * @throws DBALException
     */
    public function __invoke(ProcessImportCommand $command)
    {
        $importId = $command->getImportId();
        $lineNumber = $command->getLine();
        $record = $command->getRecord();

        $line = new ImportLine($importId, $lineNumber, '{}');

        try {
            $action = $this->importActionProvider->provide($command->getAction());

            if (!$action) {
                throw new \RuntimeException(sprintf('Can\'t find action %s', $command->getAction()));
            }

            $action->action($command->getImportId(), $record);
        } catch (\Throwable $exception) {
            $line->addError($exception->getMessage());
        }

        $this->repository->save($line);
    }
}
