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
use Ergonode\Importer\Infrastructure\Action\VariableProductImportAction;
use Ergonode\Importer\Domain\Command\Import\ImportVariableProductCommand;

/**
 */
class ImportVariableProductCommandHandler
{
    /**
     * @var VariableProductImportAction
     */
    private VariableProductImportAction $action;

    /**
     * @var ImportErrorRepositoryInterface
     */
    private ImportErrorRepositoryInterface $repository;

    /**
     * @param VariableProductImportAction    $action
     * @param ImportErrorRepositoryInterface $repository
     */
    public function __construct(VariableProductImportAction $action, ImportErrorRepositoryInterface $repository)
    {
        $this->action = $action;
        $this->repository = $repository;
    }

    /**
     * @param ImportVariableProductCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(ImportVariableProductCommand $command)
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
            $message = $exception->getMessage();
            $error = new ImportError($command->getImportId(), $message);
            $this->repository->add($error);
        }
    }
}
