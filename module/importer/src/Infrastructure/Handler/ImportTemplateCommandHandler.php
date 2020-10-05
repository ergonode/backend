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
use Ergonode\Importer\Domain\Command\Import\ImportTemplateCommand;
use Ergonode\Importer\Infrastructure\Action\TemplateImportAction;

/**
 */
class ImportTemplateCommandHandler
{
    /**
     * @var TemplateImportAction
     */
    private TemplateImportAction $action;

    /**
     * @var ImportErrorRepositoryInterface
     */
    private ImportErrorRepositoryInterface $repository;

    /**
     * @param TemplateImportAction           $action
     * @param ImportErrorRepositoryInterface $repository
     */
    public function __construct(TemplateImportAction $action, ImportErrorRepositoryInterface $repository)
    {
        $this->action = $action;
        $this->repository = $repository;
    }

    /**
     * @param ImportTemplateCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(ImportTemplateCommand $command)
    {
        try {
            $this->action->action($command->getCode());
        } catch (ImportException $exception) {
            $message = $exception->getMessage();
            $error = new ImportError($command->getImportId(), $message);
            $this->repository->add($error);
        }
    }
}
