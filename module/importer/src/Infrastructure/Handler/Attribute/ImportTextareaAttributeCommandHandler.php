<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler\Attribute;

use Ergonode\Importer\Domain\Command\Attribute\ImportTextareaAttributeCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\TextareaAttributeImportAction;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Psr\Log\LoggerInterface;

class ImportTextareaAttributeCommandHandler extends AbstractImportAttributeCommandHandler
{
    private TextareaAttributeImportAction $textareaAttributeImportAction;

    public function __construct(
        ImportRepositoryInterface $importerRepository,
        LoggerInterface $logger,
        TextareaAttributeImportAction $textareaAttributeImportAction
    ) {
        parent::__construct($importerRepository, $logger);
        $this->textareaAttributeImportAction = $textareaAttributeImportAction;
    }

    public function __invoke(ImportTextareaAttributeCommand $command): void
    {
        try {
            $this->textareaAttributeImportAction->action($command);
        } catch (ImportException $exception) {
            $this->processImportException($command, $exception);
        } catch (\Exception $exception) {
            $this->processException($command, $exception);
        }
    }
}
