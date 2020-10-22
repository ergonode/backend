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
use Psr\Log\LoggerInterface;

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
     * @var LoggerInterface
     */
    private LoggerInterface $importLogger;

    /**
     * @param OptionImportAction             $action
     * @param ImportErrorRepositoryInterface $repository
     * @param LoggerInterface                $importLogger
     */
    public function __construct(
        OptionImportAction $action,
        ImportErrorRepositoryInterface $repository,
        LoggerInterface $importLogger
    ) {
        $this->action = $action;
        $this->repository = $repository;
        $this->importLogger = $importLogger;
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
            $this->repository->add(ImportError::createFromImportException($command->getImportId(), $exception));
        } catch (\Exception $exception) {
            $message = sprintf(
                'Can\'t import options %s for attribute %s',
                $command->getKey()->getValue(),
                $command->getCode()->getValue()
            );
            $error = new ImportError($command->getImportId(), $message);
            $this->repository->add($error);
            $this->importLogger->error($exception);
        }
    }
}
