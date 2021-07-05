<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Domain\Command\Import\Attribute\ImportProductAttributesValueCommand;
use Ergonode\Importer\Infrastructure\Filter\AttributeImportFilter;
use Ergonode\Importer\Infrastructure\Filter\AttributeToRedispatchImportFilter;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Domain\Command\Import\ImportSimpleProductCommand;
use Ergonode\Importer\Infrastructure\Action\SimpleProductImportAction;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Psr\Log\LoggerInterface;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Product\Domain\ValueObject\Sku;

class ImportSimpleProductCommandHandler
{
    private SimpleProductImportAction $action;

    private ImportRepositoryInterface $repository;

    private LoggerInterface $logger;

    private AttributeToRedispatchImportFilter $attributeToRedispatchImportFilter;

    private CommandBusInterface $commandBus;

    private AttributeImportFilter $attributeImportFilter;

    public function __construct(
        SimpleProductImportAction $action,
        ImportRepositoryInterface $repository,
        LoggerInterface $logger,
        AttributeToRedispatchImportFilter $attributeToRedispatchImportFilter,
        CommandBusInterface $commandBus,
        AttributeImportFilter $attributeImportFilter
    ) {
        $this->action = $action;
        $this->repository = $repository;
        $this->logger = $logger;
        $this->attributeToRedispatchImportFilter = $attributeToRedispatchImportFilter;
        $this->commandBus = $commandBus;
        $this->attributeImportFilter = $attributeImportFilter;
    }

    public function __invoke(ImportSimpleProductCommand $command): void
    {
        try {
            if (!Sku::isValid($command->getSku())) {
                throw new ImportException('Sku {sku} is not valid', ['{sku}' => $command->getSku()]);
            }

            $categories = [];
            foreach ($command->getCategories() as $category) {
                if (!CategoryCode::isValid($category)) {
                    throw new ImportException('Category code {code} is not valid', ['{code}' => $category]);
                }
                $categories[] = new CategoryCode($category);
            }
            $filteredAttributes = $this->attributeImportFilter->filter($command->getAttributes(), $command->getSku());
            $attributesToRedispatch = $this->attributeToRedispatchImportFilter->filter($filteredAttributes);
            $validatedAttributes = array_diff_key($filteredAttributes, $attributesToRedispatch);
            $product = $this->action->action(
                new Sku($command->getSku()),
                $command->getTemplate(),
                $categories,
                $validatedAttributes,
            );

            if (!empty($attributesToRedispatch)) {
                $ImportProductAttributesValueCommand = new ImportProductAttributesValueCommand(
                    $command->getId(),
                    $command->getImportId(),
                    $attributesToRedispatch,
                    $command->getSku()
                );
                $this->commandBus->dispatch($ImportProductAttributesValueCommand, true);
            }
            $this->repository->markLineAsSuccess($command->getId(), $product->getId());
        } catch (ImportException $exception) {
            $this->repository->markLineAsFailure($command->getId());
            $this->repository->addError($command->getImportId(), $exception->getMessage(), $exception->getParameters());
        } catch (\Exception $exception) {
            $message = 'Can\'t import simple product {sku}';
            $this->repository->markLineAsFailure($command->getId());
            $this->repository->addError($command->getImportId(), $message, ['{sku}' => $command->getSku()]);
            $this->logger->error($exception);
        }
    }
}
