<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\OptionImportAction;
use Ergonode\Importer\Domain\Command\Import\ImportOptionCommand;
use Psr\Log\LoggerInterface;

class ImportOptionCommandHandler
{
    private OptionImportAction $action;

    private ImportRepositoryInterface $repository;

    private LoggerInterface $logger;

    public function __construct(
        OptionImportAction $action,
        ImportRepositoryInterface $repository,
        LoggerInterface $logger
    ) {
        $this->action = $action;
        $this->repository = $repository;
        $this->logger = $logger;
    }

    public function __invoke(ImportOptionCommand $command): void
    {
        try {
            $this->action->action(
                $command->getCode(),
                $command->getKey(),
                $command->getTranslation()
            );
        } catch (ImportException $exception) {
            $this->repository->addError($command->getImportId(), $exception->getMessage(), $exception->getParameters());
        } catch (\Exception $exception) {
            $message = 'Can\'t import options {option} for attribute {attribute}';

            $parameters = [
                '{option}' => $command->getKey()->getValue(),
                '{attribute}' => $command->getCode()->getValue(),
            ];

            $this->repository->addError($command->getImportId(), $message, $parameters);
            $this->logger->error($exception);
        }
    }
}
