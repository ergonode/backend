<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler\Attribute;

use Ergonode\Importer\Domain\Command\Import\Attribute\ImportUpdateAttributesInProductCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\ImportUpdateProductAttributesAction;
use Ergonode\Importer\Infrastructure\Exception\ImportException;

class ImportUpdateAttributesInProductCommandHandler
{
    private ImportRepositoryInterface $repository;
    private ImportUpdateProductAttributesAction $action;

    public function __construct(ImportRepositoryInterface $repository, ImportUpdateProductAttributesAction $action)
    {
        $this->repository = $repository;
        $this->action = $action;
    }

    public function __invoke(ImportUpdateAttributesInProductCommand $command): void
    {
        try {
            $this->action->action($command->getSku(), $command->getAttributes());
        } catch (ImportException $exception) {
            $this->repository->addError($command->getImportId(), $exception->getMessage(), $exception->getParameters());
        }
    }
}
