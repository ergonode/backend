<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler\Attribute;

use Ergonode\Importer\Domain\Command\Attribute\ImportGalleryAttributeCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\GalleryAttributeImportAction;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Psr\Log\LoggerInterface;

class ImportGalleryAttributeCommandHandler extends AbstractImportAttributeCommandHandler
{
    private GalleryAttributeImportAction $galleryAttributeImportAction;

    public function __construct(
        ImportRepositoryInterface $importerRepository,
        LoggerInterface $logger,
        GalleryAttributeImportAction $galleryAttributeImportAction
    ) {
        parent::__construct($importerRepository, $logger);
        $this->galleryAttributeImportAction = $galleryAttributeImportAction;
    }

    public function __invoke(ImportGalleryAttributeCommand $command): void
    {
        try {
            $this->galleryAttributeImportAction->action($command);
        } catch (ImportException $exception) {
            $this->processImportException($command, $exception);
        } catch (\Exception $exception) {
            $this->processException($command, $exception);
        }
    }
}
