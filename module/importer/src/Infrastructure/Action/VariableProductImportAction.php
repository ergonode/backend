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
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Importer\Infrastructure\Exception\ImportRelatedProductNotFoundException;
use Ergonode\Importer\Infrastructure\Exception\ImportRelatedProductIncorrectTypeException;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Importer\Infrastructure\Action\Process\Product\ImportProductAttributeBuilder;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Importer\Infrastructure\Exception\ImportBindingAttributeNotFoundException;
use Ergonode\Importer\Infrastructure\Exception\ImportIncorrectBindingAttributeException;

class VariableProductImportAction
{
    private ProductQueryInterface $productQuery;

    private ProductRepositoryInterface $productRepository;

    private AttributeQueryInterface $attributeQuery;

    private AttributeRepositoryInterface $attributeRepository;

    private TemplateQueryInterface $templateQuery;

    private CategoryQueryInterface $categoryQuery;

    private ImportProductAttributeBuilder $builder;

    public function __construct(
        ProductQueryInterface $productQuery,
        ProductRepositoryInterface $productRepository,
        AttributeQueryInterface $attributeQuery,
        AttributeRepositoryInterface $attributeRepository,
        TemplateQueryInterface $templateQuery,
        CategoryQueryInterface $categoryQuery,
        ImportProductAttributeBuilder $builder
    ) {
        $this->productQuery = $productQuery;
        $this->productRepository = $productRepository;
        $this->attributeQuery = $attributeQuery;
        $this->attributeRepository = $attributeRepository;
        $this->templateQuery = $templateQuery;
        $this->categoryQuery = $categoryQuery;
        $this->builder = $builder;
    }

    /**
     * @param array $categories
     * @param array $bindings
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
        array $bindings,
        array $children,
        array $attributes = []
    ): VariableProduct {
        $templateId = $this->templateQuery->findTemplateIdByCode($template);
        Assert::notNull($templateId);
        $productId = $this->productQuery->findProductIdBySku($sku);
        $categories = $this->getCategories($categories);
        $attributes = $this->builder->build($attributes);
        $bindings = $this->getBindings($bindings, $sku);
        $children = $this->getChildren($sku, $children);

        if (!$productId) {
            $productId = ProductId::generate();
            $product = new VariableProduct(
                $productId,
                $sku,
                $templateId,
                $categories,
                $attributes,
            );
        } else {
            $product = $this->productRepository->load($productId);
        }
        if (!$product instanceof VariableProduct) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    VariableProduct::class,
                    get_debug_type($product)
                )
            );
        }
        $product->changeTemplate($templateId);
        $product->changeCategories($categories);
        $product->changeAttributes($attributes);
        $product->changeBindings($bindings);
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
     * @param array $bindings
     *
     * @return array
     *
     * @throws ImportBindingAttributeNotFoundException
     * @throws ImportIncorrectBindingAttributeException
     */
    public function getBindings(array $bindings, Sku $sku): array
    {
        $result = [];
        foreach ($bindings as $binding) {
            $attributeId = $this->attributeQuery->findAttributeIdByCode($binding);
            if (null === $attributeId) {
                throw new ImportBindingAttributeNotFoundException($binding, $sku);
            }
            $bindingClass = $this->attributeRepository->load($attributeId);

            if (!$bindingClass instanceof SelectAttribute) {
                throw new ImportIncorrectBindingAttributeException($binding, $sku);
            }

            $result[] = $bindingClass;
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
            $result[] = $categoryId;
        }

        return $result;
    }
}
