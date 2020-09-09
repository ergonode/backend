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
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;

/**
 */
class SimpleProductImportAction implements ImportActionInterface
{
    public const TYPE = 'SIMPLE-PRODUCT';

    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $productQuery;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $repository;

    /**
     * @var ProductImportBuilderInterface[] $builders
     */
    private array $builders;

    /**
     * @param ProductQueryInterface           $productQuery
     * @param ProductRepositoryInterface      $repository
     * @param ProductImportBuilderInterface[] $builders
     */
    public function __construct(
        ProductQueryInterface $productQuery,
        ProductRepositoryInterface $repository,
        array $builders
    ) {
        $this->productQuery = $productQuery;
        $this->repository = $repository;
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

        Assert::notNull($sku, 'product import required "sku" field not exists');

        $importedProduct = new ImportedProduct($sku->getValue());

        foreach ($this->builders as $builder) {
            $importedProduct = $builder->build($importedProduct, $record);
        }

        $productId = $this->productQuery->findProductIdBySku($sku);
        $templateId = new TemplateId($importedProduct->template);

        if (!$productId) {
            $product = new SimpleProduct(
                ProductId::generate(),
                $sku,
                $templateId,
                $importedProduct->categories,
                $importedProduct->attributes,
            );
        } else {
            $product = $this->repository->load($productId);
            Assert::isInstanceOf($product, SimpleProduct::class);
        }

        $product->changeTemplate($templateId);
        $product->changeCategories($importedProduct->categories);
        $product->changeAttributes($importedProduct->attributes);

        $this->repository->save($product);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
