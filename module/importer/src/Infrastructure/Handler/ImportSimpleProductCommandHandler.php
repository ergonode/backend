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
use Ergonode\Importer\Domain\Command\Import\ImportSimpleProductCommand;
use Ergonode\Importer\Infrastructure\Action\SimpleProductImportAction;
use Psr\Log\LoggerInterface;

/**
 */
class ImportSimpleProductCommandHandler
{
    /**
     * @var SimpleProductImportAction
     */
    private SimpleProductImportAction $action;

    /**
     * @var ImportErrorRepositoryInterface
     */
    private ImportErrorRepositoryInterface $repository;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $importLogger;

    /**
     * @param SimpleProductImportAction      $action
     * @param ImportErrorRepositoryInterface $repository
     * @param LoggerInterface                $importLogger
     */
    public function __construct(
        SimpleProductImportAction $action,
        ImportErrorRepositoryInterface $repository,
        LoggerInterface $importLogger
    ) {
        $this->action = $action;
        $this->repository = $repository;
        $this->importLogger = $importLogger;
    }

    /**
     * @param ImportSimpleProductCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(ImportSimpleProductCommand $command)
    {
        try {
            $this->action->action(
                $command->getSku(),
                $command->getTemplate(),
                $command->getCategories(),
                $command->getAttributes()
            );
        } catch (ImportException $exception) {
            $this->repository->add(ImportError::createFromImportException($command->getImportId(), $exception));
        } catch (\Exception $exception) {
            $message = sprintf('Can\'t import simple product %s', $command->getSku()->getValue());
            $error = new ImportError($command->getImportId(), $message);
            $this->repository->add($error);
            $this->importLogger->error($exception);
        }
    }
}
