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
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;

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
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @var ProductImportBuilderInterface[] $builders
     */
    private array $builders;

    /**
     * @param ProductQueryInterface           $productQuery
     * @param ProductRepositoryInterface      $productRepository
     * @param AttributeRepositoryInterface    $attributeRepository
     * @param ProductImportBuilderInterface[] $builders
     */
    public function __construct(
        ProductQueryInterface $productQuery,
        ProductRepositoryInterface $productRepository,
        AttributeRepositoryInterface $attributeRepository,
        array $builders
    ) {
        $this->productQuery = $productQuery;
        $this->productRepository = $productRepository;
        $this->attributeRepository = $attributeRepository;
        $this->builders = $builders;
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
                $binding = $this->attributeRepository->load(new AttributeId($binding));
                Assert::isInstanceOf($binding, SelectAttribute::class);
                $bindings[] = $binding;
            }
        }

        $children = [];
        if ($record->has('children')) {
            foreach (explode(',', $record->get('children')) as $child) {
                $child = $this->productRepository->load(new ProductId($child));
                Assert::isInstanceOf($child, SimpleProduct::class);
                $children[] = $child;
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
            $product = new VariableProduct(
                $productId,
                $sku,
                $templateId,
                $importedProduct->categories,
                $importedProduct->attributes,
            );
        } else {
            $product = $this->productRepository->load($productId);
            Assert::isInstanceOf($product, VariableProduct::class);
        }

        $product->changeTemplate($templateId);
        $product->changeCategories($importedProduct->categories);
        $product->changeAttributes($importedProduct->attributes);
        $product->changeBindings($bindings);
        $product->changeChildren($children);

        $this->productRepository->save($product);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
