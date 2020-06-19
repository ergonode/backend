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
use Ergonode\Product\Domain\Command\Relations\AddProductChildrenCommand;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Ergonode\Product\Domain\Command\Create\CreateGroupingProductCommand;
use Ergonode\Product\Domain\Command\Update\UpdateGroupingProductCommand;

/**
 */
class GroupedProductImportAction implements ImportActionInterface
{
    public const TYPE = 'GROUPED-PRODUCT';

    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $productQuery;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var ProductImportBuilderInterface ...$builders
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

        $children = [];
        if ($record->has('children')) {
            foreach (explode(',', $record->get('children')) as $child) {
                $children[] = new ProductId($child);
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
            $command = new CreateGroupingProductCommand(
                $productId,
                $sku,
                $templateId,
                $importedProduct->categories,
                $importedProduct->attributes,
            );
        } else {
            $command = new UpdateGroupingProductCommand(
                $productId,
                $templateId,
                $importedProduct->categories,
                $importedProduct->attributes,
            );
        }

        $this->commandBus->dispatch($command, true);

        if (!empty($children)) {
            /** @var AbstractAssociatedProduct $product */
            $product = $this->productRepository->load($productId);
            $command = new AddProductChildrenCommand($product, $children);
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
