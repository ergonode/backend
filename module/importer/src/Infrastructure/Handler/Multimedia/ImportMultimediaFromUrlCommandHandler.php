<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler\Multimedia;

use Ergonode\Importer\Domain\Command\Import\ImportMultimediaFromWebCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\MultimediaFromUrlImportAction;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Psr\Log\LoggerInterface;

class ImportMultimediaFromUrlCommandHandler
{
    private MultimediaFromUrlImportAction $action;

    private ImportRepositoryInterface $repository;

    private LoggerInterface $logger;

    public function __construct(
        MultimediaFromUrlImportAction $action,
        ImportRepositoryInterface $repository,
        LoggerInterface $logger
    ) {
        $this->action = $action;
        $this->repository = $repository;
        $this->logger = $logger;
    }

    public function __invoke(ImportMultimediaFromWebCommand $command): void
    {
        try {
            $id = $this->action->action(
                $command->getImportId(),
                $command->getUrl(),
                $command->getName()
            );
            $this->repository->markLineAsSuccess($command->getId(), $id);
        } catch (ImportException $exception) {
            $this->repository->markLineAsFailure($command->getId());
            $this->repository->addError($command->getImportId(), $exception->getMessage(), $exception->getParameters());
        } catch (\Exception $exception) {
            $message = 'Can\'t import multimedia {name} from url {url}';
            $this->repository->markLineAsFailure($command->getId());
            $this->repository->addError(
                $command->getImportId(),
                $message,
                [
                    '{name}' => $command->getName(),
                    '{url}' => $command->getUrl(),
                ]
            );
            $this->logger->error($exception);
        }
    }
}
