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
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Psr\Log\LoggerInterface;

class ImportCategoryCommandHandler
{
    private CategoryImportAction $action;

    private ImportRepositoryInterface $repository;

    private LoggerInterface $logger;

    public function __construct(
        CategoryImportAction $action,
        ImportRepositoryInterface $repository,
        LoggerInterface $logger
    ) {
        $this->action = $action;
        $this->repository = $repository;
        $this->logger = $logger;
    }

    public function __invoke(ImportCategoryCommand $command)
    {
        try {
            $this->action->action(
                $command->getCode(),
                $command->getName(),
            );
        } catch (ImportException $exception) {
            $this->repository->addError($command->getImportId(), $exception->getMessage());
        } catch (\Exception $exception) {
            $message = sprintf('Can\'t import category product %s', $command->getName());
            $this->repository->addError($command->getImportId(), $message);
            $this->logger->error($exception);
        }
    }
}
