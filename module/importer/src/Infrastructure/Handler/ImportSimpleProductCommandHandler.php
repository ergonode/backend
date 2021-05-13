<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Handler;

use Ergonode\Importer\Domain\Command\Import\Attribute\ImportProductAttributesValueCommand;
use Ergonode\Importer\Infrastructure\Filter\AttributeImportFilter;
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

    private AttributeImportFilter $attributeImportFilter;

    private CommandBusInterface $commandBus;

    public function __construct(
        SimpleProductImportAction $action,
        ImportRepositoryInterface $repository,
        LoggerInterface $logger,
        AttributeImportFilter $attributeImportFilter,
        CommandBusInterface $commandBus
    ) {
        $this->action = $action;
        $this->repository = $repository;
        $this->logger = $logger;
        $this->attributeImportFilter = $attributeImportFilter;
        $this->commandBus = $commandBus;
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
            $attributesToRedispatch = $this->attributeImportFilter->filter($command->getAttributes());
            $validatedAttributes = array_diff_key($command->getAttributes(), $attributesToRedispatch);
            $product = $this->action->action(
                new Sku($command->getSku()),
                $command->getTemplate(),
                $categories,
                $validatedAttributes,
            );

            if (!empty($attributesToRedispatch)) {
                $this->commandBus->dispatch(new ImportProductAttributesValueCommand(
                    $command->getId(),
                    $command->getImportId(),
                    $attributesToRedispatch,
                    $command->getSku()
                ));
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
