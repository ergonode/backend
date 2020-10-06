<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler\Multimedia;

use Ergonode\Importer\Domain\Command\Import\ImportMultimediaCommand;
use Ergonode\Importer\Domain\Entity\ImportError;
use Ergonode\Importer\Domain\Repository\ImportErrorRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\MultimediaImportAction;
use Ergonode\Importer\Infrastructure\Exception\ImportException;

/**
 */
final class ImportMultimediaCommandHandler
{
    /**
     * @var MultimediaImportAction
     */
    private MultimediaImportAction $action;

    /**
     * @var ImportErrorRepositoryInterface
     */
    private ImportErrorRepositoryInterface $repository;

    /**
     * @param MultimediaImportAction         $action
     * @param ImportErrorRepositoryInterface $repository
     */
    public function __construct(MultimediaImportAction $action, ImportErrorRepositoryInterface $repository)
    {
        $this->action = $action;
        $this->repository = $repository;
    }

    /**
     * @param ImportMultimediaCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(ImportMultimediaCommand $command)
    {
        try {
            $this->action->action(
                $command->getImportId(),
                $command->getUrl(),
                $command->getName()
            );
        } catch (ImportException $exception) {
            $message = $exception->getMessage();
            $error = new ImportError($command->getImportId(), $message);
            $this->repository->add($error);
        }
    }
}