<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Domain\Command\Import\Attribute\ImportProductAttributesValueCommand;
use Ergonode\Importer\Infrastructure\Action\AttributeValidatorImportAction;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Domain\Command\Import\ImportGroupingProductCommand;
use Ergonode\Importer\Infrastructure\Action\GroupingProductImportAction;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Psr\Log\LoggerInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Category\Domain\ValueObject\CategoryCode;

class ImportGroupingProductCommandHandler
{
    private GroupingProductImportAction $productImportAction;

    private ImportRepositoryInterface $repository;

    private LoggerInterface $logger;

    private AttributeValidatorImportAction $attributeValidatorImportAction;

    private CommandBusInterface $commandBus;

    public function __construct(
        GroupingProductImportAction $productImportAction,
        ImportRepositoryInterface $repository,
        LoggerInterface $logger,
        AttributeValidatorImportAction $attributeValidatorImportAction,
        CommandBusInterface $commandBus
    ) {
        $this->productImportAction = $productImportAction;
        $this->repository = $repository;
        $this->logger = $logger;
        $this->attributeValidatorImportAction = $attributeValidatorImportAction;
        $this->commandBus = $commandBus;
    }

    public function __invoke(ImportGroupingProductCommand $command): void
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

            $children = [];
            foreach ($command->getChildren() as $child) {
                if (!Sku::isValid($child)) {
                    throw new ImportException('Child sku {code} is not valid', ['{code}' => $child]);
                }
                $children[] = new Sku($child);
            }
            $attributesToRedispatch = $this->attributeValidatorImportAction->action($command->getAttributes());
            $validatedAttributes = array_diff_key($command->getAttributes(), $attributesToRedispatch);
            $sku = new Sku($command->getSku());
            $product = $this->productImportAction->action(
                $sku,
                $command->getTemplate(),
                $categories,
                $children,
                $validatedAttributes
            );

            if (!empty($attributesToRedispatch)) {
                $this->commandBus->dispatch(new ImportProductAttributesValueCommand(
                    $command->getId(),
                    $command->getImportId(),
                    $attributesToRedispatch,
                    $sku
                ));
            }
            $this->repository->markLineAsSuccess($command->getId(), $product->getId());
        } catch (ImportException $exception) {
            $this->repository->markLineAsFailure($command->getId());
            $this->repository->addError($command->getImportId(), $exception->getMessage(), $exception->getParameters());
        } catch (\Exception $exception) {
            $message = 'Can\'t import grouping product {sku}';
            $this->repository->markLineAsFailure($command->getId());
            $this->repository->addError($command->getImportId(), $message, ['{sku}' => $command->getSku()]);
            $this->logger->error($exception);
        }
    }
}
