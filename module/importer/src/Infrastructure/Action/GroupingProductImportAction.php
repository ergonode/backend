<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Entity\GroupingProduct;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Importer\Infrastructure\Exception\ImportRelatedProductNotFoundException;
use Ergonode\Importer\Infrastructure\Exception\ImportRelatedProductIncorrectTypeException;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Importer\Infrastructure\Action\Process\Product\ImportProductAttributeBuilder;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

class GroupingProductImportAction
{
    private ProductQueryInterface $productQuery;

    private ProductRepositoryInterface $productRepository;

    private TemplateQueryInterface $templateQuery;

    private CategoryQueryInterface $categoryQuery;

    private ImportProductAttributeBuilder $builder;

    public function __construct(
        ProductQueryInterface $productQuery,
        ProductRepositoryInterface $productRepository,
        TemplateQueryInterface $templateQuery,
        CategoryQueryInterface $categoryQuery,
        ImportProductAttributeBuilder $builder
    ) {
        $this->productQuery = $productQuery;
        $this->productRepository = $productRepository;
        $this->templateQuery = $templateQuery;
        $this->categoryQuery = $categoryQuery;
        $this->builder = $builder;
    }

    /**
     * @param array $categories
     * @param array $children
     * @param array $attributes
     *
     * @throws ImportRelatedProductIncorrectTypeException
     * @throws ImportRelatedProductNotFoundException
     * @throws \Exception
     */
    public function action(
        Sku $sku,
        string $template,
        array $categories,
        array $children,
        array $attributes = []
    ): GroupingProduct {
        $templateId = $this->templateQuery->findTemplateIdByCode($template);
        Assert::notNull($templateId);
        $productId = $this->productQuery->findProductIdBySku($sku);
        $categories = $this->getCategories($categories);
        $attributes = $this->builder->build($attributes);
        $children = $this->getChildren($sku, $children);

        if (!$productId) {
            $productId = ProductId::generate();
            $product = new GroupingProduct(
                $productId,
                $sku,
                $templateId,
                $categories,
                $attributes,
            );
        } else {
            $product = $this->productRepository->load($productId);
        }
        if (!$product instanceof GroupingProduct) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    GroupingProduct::class,
                    get_debug_type($product)
                )
            );
        }
        $product->changeTemplate($templateId);
        $product->changeCategories($categories);
        $product->changeAttributes($attributes);
        $product->changeChildren($children);

        $this->productRepository->save($product);

        return $product;
    }

    /**
     * @param Sku[] $children
     *
     * @return AbstractProduct[]
     *
     * @throws ImportRelatedProductIncorrectTypeException
     * @throws ImportRelatedProductNotFoundException
     */
    public function getChildren(Sku $sku, array $children): array
    {
        $result = [];
        foreach ($children as $child) {
            $productId = $this->productQuery->findProductIdBySku($child);
            if (null === $productId) {
                throw new ImportRelatedProductNotFoundException($sku, $child);
            }
            $child = $this->productRepository->load($productId);
            if (!$child instanceof SimpleProduct) {
                throw new ImportRelatedProductIncorrectTypeException($sku, $child->getType());
            }
            $result[] = $child;
        }

        return $result;
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
