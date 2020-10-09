<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Domain\Command\Import\ImportAttributeCommand;
use Ergonode\Importer\Domain\Repository\ImportErrorRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\AttributeImportAction;

/**
 */
final class ImportAttributeCommandHandler
{
    /**
     * @var AttributeImportAction
     */
    private AttributeImportAction $action;

    /**
     * @var ImportErrorRepositoryInterface
     */
    private ImportErrorRepositoryInterface $repository;

    /**
     * @param AttributeImportAction          $action
     * @param ImportErrorRepositoryInterface $repository
     */
    public function __construct(AttributeImportAction $action, ImportErrorRepositoryInterface $repository)
    {
        $this->action = $action;
        $this->repository = $repository;
    }

    /**
     * @param ImportAttributeCommand $command
     */
    public function __invoke(ImportAttributeCommand $command): void
    {

    }
}