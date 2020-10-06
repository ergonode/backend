<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Handler\Multimedia;

use Ergonode\Importer\Domain\Command\Import\ImportMultimediaFromWebCommand;
use Ergonode\Importer\Domain\Entity\ImportError;
use Ergonode\Importer\Domain\Repository\ImportErrorRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\MultimediaFromUrlImportAction;
use Ergonode\Importer\Infrastructure\Exception\ImportException;

/**
 */
class ImportMultimediaFromUrlCommandHandler
{
    /**
     * @var MultimediaFromUrlImportAction
     */
    private MultimediaFromUrlImportAction $action;

    /**
     * @var ImportErrorRepositoryInterface
     */
    private ImportErrorRepositoryInterface $repository;

    /**
     * @param MultimediaFromUrlImportAction         $action
     * @param ImportErrorRepositoryInterface $repository
     */
    public function __construct(MultimediaFromUrlImportAction $action, ImportErrorRepositoryInterface $repository)
    {
        $this->action = $action;
        $this->repository = $repository;
    }

    /**
     * @param ImportMultimediaFromWebCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(ImportMultimediaFromWebCommand $command)
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
