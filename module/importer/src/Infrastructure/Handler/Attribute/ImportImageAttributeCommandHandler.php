<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler\Attribute;

use Ergonode\Importer\Domain\Command\Attribute\ImportImageAttributeCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\ImageAttributeImportAction;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Psr\Log\LoggerInterface;

class ImportImageAttributeCommandHandler extends AbstractImportAttributeCommandHandler
{
    private ImageAttributeImportAction $imageAttributeImportAction;

    public function __construct(
        ImportRepositoryInterface $importerRepository,
        LoggerInterface $logger,
        ImageAttributeImportAction $imageAttributeImportAction
    ) {
        parent::__construct($importerRepository, $logger);
        $this->imageAttributeImportAction = $imageAttributeImportAction;
    }

    public function __invoke(ImportImageAttributeCommand $command): void
    {
        try {
            $this->imageAttributeImportAction->action($command);
        } catch (ImportException $exception) {
            $this->processImportException($command, $exception);
        } catch (\Exception $exception) {
            $this->processException($command, $exception);
        }
    }
}
