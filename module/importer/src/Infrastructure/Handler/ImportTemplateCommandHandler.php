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

    public function __invoke(ImportTemplateCommand $command): void
    {
        try {
            $template = $this->action->action($command->getCode());
            $this->repository->addLine($command->getImportId(), $template->getId(), 'TEMPLATE');
        } catch (ImportException $exception) {
            $this->repository->addError($command->getImportId(), $exception->getMessage(), $exception->getParameters());
        } catch (\Exception $exception) {
            $message = 'Can\'t import template {template}';
            $this->repository->addError($command->getImportId(), $message, ['{template}' => $command->getCode()]);
            $this->logger->error($exception);
        }
    }
}
