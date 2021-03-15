<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler\Attribute;

use Ergonode\Importer\Domain\Command\Import\Attribute\ImportGalleryAttributeCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\GalleryAttributeImportAction;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Psr\Log\LoggerInterface;

class ImportGalleryAttributeCommandHandler extends AbstractImportAttributeCommandHandler
{
    private GalleryAttributeImportAction $action;

    public function __construct(
        ImportRepositoryInterface $importerRepository,
        LoggerInterface $logger,
        GalleryAttributeImportAction $action
    ) {
        parent::__construct($importerRepository, $logger);
        $this->action = $action;
    }

    public function __invoke(ImportGalleryAttributeCommand $command): void
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
