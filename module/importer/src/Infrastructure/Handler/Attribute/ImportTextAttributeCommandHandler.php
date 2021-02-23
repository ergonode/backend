<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler\Attribute;

use Ergonode\Importer\Domain\Command\Attribute\ImportTextAttributeCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\TextAttributeImportAction;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Psr\Log\LoggerInterface;

class ImportTextAttributeCommandHandler extends AbstractImportAttributeCommandHandler
{
    private TextAttributeImportAction $textAttributeImportAction;

    public function __construct(
        ImportRepositoryInterface $importerRepository,
        LoggerInterface $logger,
        TextAttributeImportAction $textAttributeImportAction
    ) {
        parent::__construct($importerRepository, $logger);
        $this->textAttributeImportAction = $textAttributeImportAction;
    }

    public function __invoke(ImportTextAttributeCommand $command): void
    {
        try {
            $this->textAttributeImportAction->action($command);
        } catch (ImportException $exception) {
            $this->processImportException($command, $exception);
        } catch (\Exception $exception) {
            $this->processException($command, $exception);
        }
    }
}
