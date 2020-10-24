<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Domain\Command\Import\ImportTemplateCommand;
use Ergonode\Importer\Infrastructure\Action\TemplateImportAction;
use Psr\Log\LoggerInterface;

class ImportTemplateCommandHandler
{
    private TemplateImportAction $action;

    private ImportRepositoryInterface $repository;

    private LoggerInterface $logger;

    public function __construct(
        TemplateImportAction $action,
        ImportRepositoryInterface $repository,
        LoggerInterface $logger
    ) {
        $this->action = $action;
        $this->repository = $repository;
        $this->logger = $logger;
    }

    public function __invoke(ImportTemplateCommand $command)
    {
        try {
            $this->action->action($command->getCode());
        } catch (ImportException $exception) {
            $this->repository->addError($command->getImportId(), $exception->getMessage());
        } catch (\Exception $exception) {
            $message = sprintf('Can\'t import template %s', $command->getCode());
            $this->repository->addError($command->getImportId(), $message);
            $this->logger->error($exception);
        }
    }
}
