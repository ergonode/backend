<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\MultimediaImportAction;
use Ergonode\Importer\Domain\Command\Import\ImportMultimediaFromWebCommand;
use Psr\Log\LoggerInterface;

class ImportMultimediaFromUrlCommandHandler
{
    private MultimediaImportAction $action;

    private ImportRepositoryInterface $repository;

    private LoggerInterface $logger;

    public function __construct(
        MultimediaImportAction $action,
        ImportRepositoryInterface $repository,
        LoggerInterface $logger
    ) {
        $this->action = $action;
        $this->repository = $repository;
        $this->logger = $logger;
    }

    public function __invoke(ImportMultimediaFromWebCommand $command)
    {
        try {
            $this->action->action(
                $command->getImportId(),
                $command->getUrl(),
                $command->getName()
            );
        } catch (ImportException $exception) {
            $this->repository->addError($command->getImportId(), $exception->getMessage());
        } catch (\Exception $exception) {
            $message = sprintf('Can\'t import multimedia %s from url %s', $command->getName(), $command->getUrl());
            $this->repository->addError($command->getImportId(), $message);
            $this->logger->error($exception);
        }
    }
}
