<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

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

    public function __invoke(ImportCategoryCommand $command): void
    {
        try {
            $this->action->action(
                $command->getCode(),
                $command->getName(),
            );
        } catch (ImportException $exception) {
            $this->repository->addError($command->getImportId(), $exception->getMessage(), $exception->getParameters());
        } catch (\Exception $exception) {
            $message = 'Can\'t import category product {name}';
            $this->repository->addError($command->getImportId(), $message, ['{name}' => $command->getName()]);
            $this->logger->error($exception);
        }
    }
}
