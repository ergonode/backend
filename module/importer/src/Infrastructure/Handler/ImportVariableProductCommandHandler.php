<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\VariableProductImportAction;
use Ergonode\Importer\Domain\Command\Import\ImportVariableProductCommand;
use Psr\Log\LoggerInterface;

class ImportVariableProductCommandHandler
{
    private VariableProductImportAction $action;

    private ImportRepositoryInterface $repository;

    private LoggerInterface $logger;

    public function __construct(
        VariableProductImportAction $action,
        ImportRepositoryInterface $repository,
        LoggerInterface $logger
    ) {
        $this->action = $action;
        $this->repository = $repository;
        $this->logger = $logger;
    }

    public function __invoke(ImportVariableProductCommand $command): void
    {
        try {
            $this->action->action(
                $command->getSku(),
                $command->getTemplate(),
                $command->getCategories(),
                $command->getBindings(),
                $command->getChildren(),
                $command->getAttributes()
            );
        } catch (ImportException $exception) {
            $this->repository->addError($command->getImportId(), $exception->getMessage(), $exception->getParameters());
        } catch (\Exception $exception) {
            $message = 'Can\'t import variable product {sku}';
            $this->repository->addError($command->getImportId(), $message, ['{sku}' => $command->getSku()->getValue()]);
            $this->logger->error($exception);
        }
    }
}
