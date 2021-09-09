<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler\Attribute;

use Ergonode\Importer\Domain\Command\Import\Attribute\ImportProductAttributesValueCommand;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Infrastructure\Action\ImportProductAttributesAction;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Product\Domain\ValueObject\Sku;
use Psr\Log\LoggerInterface;

class ImportProductAttributesValueCommandHandler
{
    private ImportRepositoryInterface $repository;
    private ImportProductAttributesAction $action;
    private LoggerInterface $logger;

    public function __construct(
        ImportRepositoryInterface $repository,
        ImportProductAttributesAction $action,
        LoggerInterface $logger
    ) {
        $this->repository = $repository;
        $this->action = $action;
        $this->logger = $logger;
    }

    public function __invoke(ImportProductAttributesValueCommand $command): void
    {
        try {
            $this->action->action(new Sku($command->getSku()), $command->getAttributes());
        } catch (ImportException $exception) {
            $this->repository->addError($command->getImportId(), $exception->getMessage(), $exception->getParameters());
        } catch (\Exception $exception) {
            $message = 'Can\'t import simple product {sku}';
            $this->repository->markLineAsFailure($command->getId());
            $this->repository->addError($command->getImportId(), $message, ['{sku}' => $command->getSku()]);
            $this->logger->error($exception);
        }
    }
}
