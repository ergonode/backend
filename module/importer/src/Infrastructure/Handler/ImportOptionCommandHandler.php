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
use Ergonode\Importer\Infrastructure\Action\OptionImportAction;
use Ergonode\Importer\Domain\Command\Import\ImportOptionCommand;

/**
 */
class ImportOptionCommandHandler
{
    /**
     * @var OptionImportAction
     */
    private OptionImportAction $action;

    /**
     * @var ImportErrorRepositoryInterface
     */
    private ImportErrorRepositoryInterface $repository;

    /**
     * @param OptionImportAction             $action
     * @param ImportErrorRepositoryInterface $repository
     */
    public function __construct(OptionImportAction $action, ImportErrorRepositoryInterface $repository)
    {
        $this->action = $action;
        $this->repository = $repository;
    }

    /**
     * @param ImportOptionCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(ImportOptionCommand $command)
    {
        try {
            $this->action->action(
                $command->getCode(),
                $command->getKey(),
                $command->getTranslation()
            );
        } catch (ImportException $exception) {
            $message = $exception->getMessage();
            $error = new ImportError($command->getImportId(), $message);
            $this->repository->add($error);
        }
    }
}
