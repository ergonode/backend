<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Domain\Entity\ImportError;
use Ergonode\Importer\Domain\Repository\ImportErrorRepositoryInterface;
use Ergonode\Importer\Domain\Command\Import\ImportGroupingProductCommand;
use Ergonode\Importer\Infrastructure\Action\GroupingProductImportAction;
use Psr\Log\LoggerInterface;

class ImportGroupingProductCommandHandler
{
    /**
     * @var GroupingProductImportAction
     */
    private GroupingProductImportAction $action;

    /**
     * @var ImportErrorRepositoryInterface
     */
    private ImportErrorRepositoryInterface $repository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $importLogger;

    /**
     * @param GroupingProductImportAction    $action
     * @param ImportErrorRepositoryInterface $repository
     * @param LoggerInterface                $importLogger
     */
    public function __construct(
        GroupingProductImportAction $action,
        ImportErrorRepositoryInterface $repository,
        LoggerInterface $importLogger
    ) {
        $this->action = $action;
        $this->repository = $repository;
        $this->importLogger = $importLogger;
    }

    /**
     * @param ImportGroupingProductCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(ImportGroupingProductCommand $command)
    {
        try {
            $this->action->action(
                $command->getSku(),
                $command->getTemplate(),
                $command->getCategories(),
                $command->getChildren(),
                $command->getAttributes()
            );
        } catch (ImportException $exception) {
            $this->repository->add(ImportError::createFromImportException($command->getImportId(), $exception));
        } catch (\Exception $exception) {
            $message = sprintf('Can\'t import grouping product %s', $command->getSku());
            $error = new ImportError($command->getImportId(), $message);
            $this->repository->add($error);
            $this->importLogger->error($exception);
        }
    }
}
