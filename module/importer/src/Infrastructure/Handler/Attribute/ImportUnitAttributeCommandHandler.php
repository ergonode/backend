<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler\Attribute;

use Ergonode\Importer\Domain\Command\Attribute\ImportUnitAttributeCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\UnitAttributeImportAction;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Psr\Log\LoggerInterface;

class ImportUnitAttributeCommandHandler extends AbstractImportAttributeCommandHandler
{
    private UnitAttributeImportAction $unitAttributeImportAction;

    public function __construct(
        ImportRepositoryInterface $importerRepository,
        LoggerInterface $logger,
        UnitAttributeImportAction $unitAttributeImportAction
    ) {
        parent::__construct($importerRepository, $logger);
        $this->unitAttributeImportAction = $unitAttributeImportAction;
    }

    public function __invoke(ImportUnitAttributeCommand $command): void
    {
        try {
            $this->unitAttributeImportAction->action($command);
        } catch (ImportException $exception) {
            $this->processImportException($command, $exception);
        } catch (\Exception $exception) {
            $this->processException($command, $exception);
        }
    }
}
