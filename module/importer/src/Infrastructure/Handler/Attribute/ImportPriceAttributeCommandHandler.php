<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler\Attribute;

use Ergonode\Importer\Domain\Command\Attribute\ImportPriceAttributeCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\PriceAttributeImportAction;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Psr\Log\LoggerInterface;

class ImportPriceAttributeCommandHandler extends AbstractImportAttributeCommandHandler
{
    private PriceAttributeImportAction $priceAttributeImportAction;

    public function __construct(
        ImportRepositoryInterface $importerRepository,
        LoggerInterface $logger,
        PriceAttributeImportAction $priceAttributeImportAction
    ) {
        parent::__construct($importerRepository, $logger);
        $this->priceAttributeImportAction = $priceAttributeImportAction;
    }

    public function __invoke(ImportPriceAttributeCommand $command): void
    {
        try {
            $this->priceAttributeImportAction->action($command);
        } catch (ImportException $exception) {
            $this->processImportException($command, $exception);
        } catch (\Exception $exception) {
            $this->processException($command, $exception);
        }
    }
}
