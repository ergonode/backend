<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Importer\Infrastructure\Action\Process\Product\ImportProductAttributeBuilder;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class SimpleProductImportAction
{
    private ProductQueryInterface $productQuery;

    private ProductRepositoryInterface $repository;

    private TemplateQueryInterface $templateQuery;

    private CategoryQueryInterface $categoryQuery;

    private ImportProductAttributeBuilder $builder;

    protected ProductFactoryInterface $productFactory;

    public function __construct(
        ProductQueryInterface $productQuery,
        ProductRepositoryInterface $repository,
        TemplateQueryInterface $templateQuery,
        CategoryQueryInterface $categoryQuery,
        ImportProductAttributeBuilder $builder,
        ProductFactoryInterface $productFactory
    ) {
        $this->productQuery = $productQuery;
        $this->repository = $repository;
        $this->templateQuery = $templateQuery;
        $this->categoryQuery = $categoryQuery;
        $this->builder = $builder;
        $this->productFactory = $productFactory;
    }

    /**
     * @param CategoryCode[]       $categories
     * @param TranslatableString[] $attributes
     *
     * @throws \Exception
     */
    public function action(
        Sku $sku,
        string $template,
        array $categories,
        array $attributes = []
    ): SimpleProduct {
        $templateId = $this->templateQuery->findTemplateIdByCode($template);
        if (null === $templateId) {
            throw new ImportException('Missing {template} template.', ['{template}' => $template]);
        }
        $productId = $this->productQuery->findProductIdBySku($sku);
        $categories = $this->getCategories($categories);
        $attributes = $this->builder->build($attributes);

        if (!$productId) {
            $product = $this->productFactory->create(
                SimpleProduct::TYPE,
                ProductId::generate(),
                $sku,
                $templateId,
                $categories,
                $attributes,
            );
        } else {
            $product = $this->repository->load($productId);
            if (!$product instanceof SimpleProduct) {
                throw new ImportException('Product {sku} is not a simple product', ['{sku}' => $sku]);
            }
            $product->changeTemplate($templateId);
            $product->changeCategories($categories);
            $product->changeAttributes($attributes);
        }

        $this->repository->save($product);

        return $product;
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
            if (null === $categoryId) {
                throw new ImportException('Missing {category} category', ['{category}' => $category]);
            }
            $result[] = $categoryId;
        }

        return $result;
    }
}
