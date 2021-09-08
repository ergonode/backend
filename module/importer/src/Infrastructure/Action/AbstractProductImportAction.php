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
use Ergonode\Importer\Infrastructure\Exception\ImportRelatedProductIncorrectTypeException;
use Ergonode\Importer\Infrastructure\Exception\ImportRelatedProductNotFoundException;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\Attribute\CreatedAtSystemAttribute;
use Ergonode\Product\Domain\Entity\Attribute\CreatedBySystemAttribute;
use Ergonode\Product\Domain\Entity\Attribute\EditedAtSystemAttribute;
use Ergonode\Product\Domain\Entity\Attribute\EditedBySystemAttribute;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;

abstract class AbstractProductImportAction
{
    private CategoryQueryInterface $categoryQuery;

    protected ProductQueryInterface $productQuery;

    protected ProductRepositoryInterface $productRepository;

    protected TemplateQueryInterface $templateQuery;

    protected ImportProductAttributeBuilder $builder;

    protected ProductFactoryInterface $productFactory;

    public function __construct(
        CategoryQueryInterface $categoryQuery,
        ProductQueryInterface $productQuery,
        ProductRepositoryInterface $productRepository,
        TemplateQueryInterface $templateQuery,
        ImportProductAttributeBuilder $builder,
        ProductFactoryInterface $productFactory
    ) {
        $this->categoryQuery = $categoryQuery;
        $this->productQuery = $productQuery;
        $this->productRepository = $productRepository;
        $this->templateQuery = $templateQuery;
        $this->builder = $builder;
        $this->productFactory = $productFactory;
    }

    /**
     * @param CategoryCode[] $categories
     *
     * @return CategoryId[]
     */
    protected function getCategories(array $categories): array
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

    /**
     * @param Sku[] $children
     *
     * @return AbstractProduct[]
     *
     * @throws ImportRelatedProductIncorrectTypeException
     * @throws ImportRelatedProductNotFoundException
     */
    protected function getChildren(Sku $sku, array $children): array
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

    protected function mergeSystemAttributes(array $productAttributes, array $importAttributes): array
    {
        $systemAttributes = [
            StatusSystemAttribute::CODE,
            CreatedAtSystemAttribute::CODE,
            EditedAtSystemAttribute::CODE,
            CreatedBySystemAttribute::CODE,
            EditedBySystemAttribute::CODE,
        ];

        foreach ($systemAttributes as $systemAttribute) {
            if (isset($productAttributes[$systemAttribute])) {
                $importAttributes[$systemAttribute] = $productAttributes[$systemAttribute];
            }
        }

        return $importAttributes;
    }
}
