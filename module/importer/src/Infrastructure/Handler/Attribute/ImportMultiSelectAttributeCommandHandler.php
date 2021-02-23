<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler\Attribute;

use Ergonode\Importer\Domain\Command\Attribute\ImportMultiSelectAttributeCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\MultiSelectAttributeImportAction;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Psr\Log\LoggerInterface;

class ImportMultiSelectAttributeCommandHandler extends AbstractImportAttributeCommandHandler
{
    private MultiSelectAttributeImportAction $multiSelectAttributeImportAction;

    public function __construct(
        ImportRepositoryInterface $importerRepository,
        LoggerInterface $logger,
        MultiSelectAttributeImportAction $selectAttributeImportAction
    ) {
        parent::__construct($importerRepository, $logger);
        $this->multiSelectAttributeImportAction = $selectAttributeImportAction;
    }

    public function __invoke(ImportMultiSelectAttributeCommand $command): void
    {
        try {
            $this->multiSelectAttributeImportAction->action($command);
        } catch (ImportException $exception) {
            $this->processImportException($command, $exception);
        } catch (\Exception $exception) {
            $this->processException($command, $exception);
        }
    }
}
