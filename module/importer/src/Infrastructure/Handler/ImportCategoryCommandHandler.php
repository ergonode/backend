<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Domain\Command\Import\ImportCategoryCommand;
use Ergonode\Importer\Infrastructure\Action\CategoryImportAction;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Domain\Entity\ImportError;
use Ergonode\Importer\Domain\Repository\ImportErrorRepositoryInterface;
use Psr\Log\LoggerInterface;

class ImportCategoryCommandHandler
{
    /**
     * @var CategoryImportAction
     */
    private CategoryImportAction $action;

    /**
     * @var ImportErrorRepositoryInterface
     */
    private ImportErrorRepositoryInterface $repository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $importLogger;

    /**
     * @param CategoryImportAction           $action
     * @param ImportErrorRepositoryInterface $repository
     * @param LoggerInterface                $importLogger
     */
    public function __construct(
        CategoryImportAction $action,
        ImportErrorRepositoryInterface $repository,
        LoggerInterface $importLogger
    ) {
        $this->action = $action;
        $this->repository = $repository;
        $this->importLogger = $importLogger;
    }

    /**
     * @param ImportCategoryCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(ImportCategoryCommand $command)
    {
        try {
            $this->action->action(
                $command->getCode(),
                $command->getName(),
            );
        } catch (ImportException $exception) {
            $this->repository->add(ImportError::createFromImportException($command->getImportId(), $exception));
        } catch (\Exception $exception) {
            $message = sprintf('Can\'t import category product %s', $command->getName());
            $error = new ImportError($command->getImportId(), $message);
            $this->repository->add($error);
            $this->importLogger->error($exception);
        }
    }
}
