<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Action;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Provider\ProductFactoryProvider;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Transformer\Domain\Model\ImportedProduct;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Transformer\Infrastructure\Action\Builder\ProductImportBuilderInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Webmozart\Assert\Assert;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Product\Domain\Command\CreateProductCommand;

/**
 */
class ProductImportAction implements ImportActionInterface
{
    public const TYPE = 'PRODUCT';

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

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
     * @param ProductRepositoryInterface $productRepository
     * @param ProductQueryInterface $productQuery
     * @param CommandBusInterface $commandBus
     * @param array|ProductImportBuilderInterface[] $builders
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProductQueryInterface $productQuery,
        CommandBusInterface $commandBus,
        $builders
    ) {
        $this->productRepository = $productRepository;
        $this->productQuery = $productQuery;
        $this->commandBus = $commandBus;
        $this->builders = $builders;
    }

    /**
     * @param Record $record
     *
     * @throws \Exception
     */
    public function action(Record $record): void
    {
        $sku = $record->get('sku') ? new Sku($record->get('sku')->getValue()) : null;
        Assert::notNull($sku, 'product import required "sku" field not exists');

        $importedProduct = new ImportedProduct($sku->getValue());

        foreach ($this->builders as $builder) {
            $importedProduct = $builder->build($importedProduct, $record);
        }

        $productData = $this->productQuery->findBySku($sku);

        if (!$productData) {
            $command = new CreateProductCommand(
                ProductId::generate(),
                $sku,
                $importedProduct->categories,
                $importedProduct->attributes,
            );
            $this->commandBus->dispatch($command);
        } else {
            $product = $this->productRepository->load(new ProductId($productData['id']));
            if (!$product) {
                throw new \RuntimeException(sprintf('Can\'t find product "%s"', $sku->getValue()));
            }

            foreach ($importedProduct->attributes as $code => $value) {
                $attributeCode = new AttributeCode($code);
                $this->updateProduct($product, $attributeCode, $value);
            }

            foreach ($importedProduct->categories as $category) {
                $product->addToCategory($category);
            }

            foreach ($product->getCategories() as $category) {
                if (isset($categories[$category->getValue()])) {
                    $product->removeFromCategory($category);
                }
            }
            $this->productRepository->save($product);
        }
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param AbstractProduct     $product
     * @param AttributeCode       $attributeCode
     * @param ValueInterface|null $value
     *
     * @throws \Exception
     */
    private function updateProduct(
        AbstractProduct $product,
        AttributeCode $attributeCode,
        ?ValueInterface $value = null
    ): void {
        if (null !== $value) {
            if (!$product->hasAttribute($attributeCode)) {
                $product->addAttribute($attributeCode, $value);
            } else {
                $product->changeAttribute($attributeCode, $value);
            }
        } elseif ($product->hasAttribute($attributeCode)) {
            $product->removeAttribute($attributeCode);
        }
    }
}
