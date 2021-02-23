<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler\Attribute;

use Ergonode\Importer\Domain\Command\Attribute\ImportFileAttributeCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\FileAttributeImportAction;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Psr\Log\LoggerInterface;

class ImportFileAttributeCommandHandler extends AbstractImportAttributeCommandHandler
{
    private FileAttributeImportAction $fileAttributeImportAction;

    public function __construct(
        ImportRepositoryInterface $importerRepository,
        LoggerInterface $logger,
        FileAttributeImportAction $attributeImportAction
    ) {
        parent::__construct($importerRepository, $logger);
        $this->fileAttributeImportAction = $attributeImportAction;
    }

    public function __invoke(ImportFileAttributeCommand $command): void
    {
        try {
            $this->fileAttributeImportAction->action($command);
        } catch (ImportException $exception) {
            $this->processImportException($command, $exception);
        } catch (\Exception $exception) {
            $this->processException($command, $exception);
        }
    }
}
