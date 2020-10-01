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

/**
 */
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
     * @param CategoryImportAction           $action
     * @param ImportErrorRepositoryInterface $repository
     */
    public function __construct(CategoryImportAction $action, ImportErrorRepositoryInterface $repository)
    {
        $this->action = $action;
        $this->repository = $repository;
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
            $message = $exception->getMessage();
            $error = new ImportError($command->getImportId(), $message);
            $this->repository->add($error);
        }
    }
}
