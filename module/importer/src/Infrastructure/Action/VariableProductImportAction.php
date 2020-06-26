<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Transformer\Domain\Model\ImportedProduct;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Importer\Infrastructure\Action\Builder\ProductImportBuilderInterface;
use Webmozart\Assert\Assert;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Product\Domain\Command\Create\CreateVariableProductCommand;
use Ergonode\Product\Domain\Command\Update\UpdateVariableProductCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Product\Domain\Command\Relations\AddProductChildrenCommand;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;

/**
 */
class VariableProductImportAction implements ImportActionInterface
{
    public const TYPE = 'VARIABLE-PRODUCT';

    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $productQuery;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var ProductImportBuilderInterface[] $builders
     */
    private array $builders;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @param ProductQueryInterface                 $productQuery
     * @param CommandBusInterface                   $commandBus
     * @param array|ProductImportBuilderInterface[] $builders
     * @param ProductRepositoryInterface            $productRepository
     */
    public function __construct(
        ProductQueryInterface $productQuery,
        CommandBusInterface $commandBus,
        $builders,
        ProductRepositoryInterface $productRepository
    ) {
        $this->productQuery = $productQuery;
        $this->commandBus = $commandBus;
        $this->builders = $builders;
        $this->productRepository = $productRepository;
    }

    /**
     * @param ImportId $importId
     * @param Record   $record
     *
     * @throws \Exception
     */
    public function action(ImportId $importId, Record $record): void
    {
        $sku = $record->get('sku') ? new Sku($record->get('sku')) : null;
        $bindings = [];
        if ($record->has('bindings')) {
            foreach (explode(',', $record->get('bindings')) as $binding) {
                $bindings[] = new AttributeId($binding);
            }
        }

        $variants = [];
        if ($record->has('variants')) {
            foreach (explode(',', $record->get('variants')) as $variant) {
                $variants[] = new ProductId($variant);
            }
        }

        Assert::notNull($sku, 'product import required "sku" field not exists');

        $importedProduct = new ImportedProduct($sku->getValue());

        foreach ($this->builders as $builder) {
            $importedProduct = $builder->build($importedProduct, $record);
        }

        $productId = $this->productQuery->findProductIdBySku($sku);
        $templateId = new TemplateId($importedProduct->template);

        if (!$productId) {
            $productId = ProductId::generate();
            $command = new CreateVariableProductCommand(
                $productId,
                $sku,
                $templateId,
                $importedProduct->categories,
                $bindings,
                $importedProduct->attributes,
            );
        } else {
            $command = new UpdateVariableProductCommand(
                $productId,
                $templateId,
                $importedProduct->categories,
                $bindings,
                $importedProduct->attributes,
            );
        }

        $this->commandBus->dispatch($command, true);

        if (!empty($variants)) {
            /** @var AbstractAssociatedProduct $product */
            $product = $this->productRepository->load($productId);
            $command = new AddProductChildrenCommand($product, $variants);
            $this->commandBus->dispatch($command, true);
        }
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
