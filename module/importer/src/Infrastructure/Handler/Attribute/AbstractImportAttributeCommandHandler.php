<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler\Attribute;

use Ergonode\Importer\Domain\Command\Import\Attribute\AbstractImportAttributeCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Psr\Log\LoggerInterface;

abstract class AbstractImportAttributeCommandHandler
{
    private ImportRepositoryInterface $importerRepository;

    private LoggerInterface $logger;

    public function __construct(ImportRepositoryInterface $importerRepository, LoggerInterface $logger)
    {
        $this->importerRepository = $importerRepository;
        $this->logger = $logger;
    }

    protected function processImportException(AbstractImportAttributeCommand $command, ImportException $exception): void
    {
        $this->importerRepository->markLineAsFailure($command->getId());
        $this->importerRepository->addError(
            $command->getImportId(),
            $exception->getMessage(),
            $exception->getParameters()
        );
    }

    protected function processException(AbstractImportAttributeCommand $command, \Exception $exception): void
    {
        $message = 'Can\'t import attribute product {code}';
        $this->importerRepository->markLineAsFailure($command->getId());
        $this->importerRepository->addError($command->getImportId(), $message, ['{code}' => $command->getCode()]);
        $this->logger->error($exception);
    }
}
