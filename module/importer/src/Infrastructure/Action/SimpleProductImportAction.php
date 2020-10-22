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
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Importer\Infrastructure\Action\Process\Product\ImportProductAttributeBuilder;

class SimpleProductImportAction
{
    private ProductQueryInterface $productQuery;

    private ProductRepositoryInterface $repository;

    private TemplateQueryInterface $templateQuery;

    private CategoryQueryInterface $categoryQuery;

    private ImportProductAttributeBuilder $builder;

    public function __construct(
        ProductQueryInterface $productQuery,
        ProductRepositoryInterface $repository,
        TemplateQueryInterface $templateQuery,
        CategoryQueryInterface $categoryQuery,
        ImportProductAttributeBuilder $builder
    ) {
        $this->productQuery = $productQuery;
        $this->repository = $repository;
        $this->templateQuery = $templateQuery;
        $this->categoryQuery = $categoryQuery;
        $this->builder = $builder;
    }

    /**
     * @param array $categories
     * @param array $attributes
     *
     * @throws \Exception
     */
    public function action(
        Sku $sku,
        string $template,
        array $categories,
        array $attributes = []
    ): void {
        $templateId = $this->templateQuery->findTemplateIdByCode($template);
        Assert::notNull($templateId);
        $productId = $this->productQuery->findProductIdBySku($sku);

        $categories = $this->getCategories($categories);


        $attributes = $this->builder->build($attributes);

        if (!$productId) {
            $product = new SimpleProduct(
                ProductId::generate(),
                $sku,
                $templateId,
                $categories,
                $attributes,
            );
        } else {
            $product = $this->repository->load($productId);
            Assert::isInstanceOf($product, SimpleProduct::class);
        }

        $product->changeTemplate($templateId);
        $product->changeCategories($categories);
        $product->changeAttributes($attributes);

        $this->repository->save($product);
    }

    /**
     * @param CategoryCode[] $categories
     *
     * @return CategoryId[]
     */
    public function getCategories(array $categories): array
    {
        $result = [];
        foreach ($categories as $category) {
            $categoryId = $this->categoryQuery->findIdByCode($category);
            Assert::notNull($categoryId);
            $categories[] = $categoryId;
        }

        return $result;
    }
}
