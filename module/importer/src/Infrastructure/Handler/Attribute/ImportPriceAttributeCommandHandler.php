<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler\Attribute;

use Ergonode\Importer\Domain\Command\Import\Attribute\ImportPriceAttributeCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\PriceAttributeImportAction;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Psr\Log\LoggerInterface;

class ImportPriceAttributeCommandHandler extends AbstractImportAttributeCommandHandler
{
    private PriceAttributeImportAction $action;

    public function __construct(
        ImportRepositoryInterface $importerRepository,
        LoggerInterface $logger,
        PriceAttributeImportAction $action
    ) {
        parent::__construct($importerRepository, $logger);
        $this->action = $action;
    }

    public function __invoke(ImportPriceAttributeCommand $command): void
    {
        try {
            $this->action->action($command);
        } catch (ImportException $exception) {
            $this->processImportException($command, $exception);
        } catch (\Exception $exception) {
            $this->processException($command, $exception);
        }
    }
}
