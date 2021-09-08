<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Importer\Infrastructure\Action\Process\Product\ImportProductAttributeBuilder;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Importer\Infrastructure\Exception\ImportBindingAttributeNotFoundException;
use Ergonode\Importer\Infrastructure\Exception\ImportIncorrectBindingAttributeException;

class VariableProductImportAction extends AbstractProductImportAction
{
    private AttributeQueryInterface $attributeQuery;

    private AttributeRepositoryInterface $attributeRepository;

    public function __construct(
        AttributeQueryInterface $attributeQuery,
        AttributeRepositoryInterface $attributeRepository,
        CategoryQueryInterface $categoryQuery,
        ProductQueryInterface $productQuery,
        ProductRepositoryInterface $productRepository,
        TemplateQueryInterface $templateQuery,
        ImportProductAttributeBuilder $builder,
        ProductFactoryInterface $productFactory
    ) {
        parent::__construct(
            $categoryQuery,
            $productQuery,
            $productRepository,
            $templateQuery,
            $builder,
            $productFactory
        );
        $this->attributeQuery = $attributeQuery;
        $this->attributeRepository = $attributeRepository;
    }

    public function action(
        Sku $sku,
        string $template,
        array $categories,
        array $bindings,
        array $children,
        array $attributes = []
    ): VariableProduct {
        $templateId = $this->templateQuery->findTemplateIdByCode($template);
        if (null === $templateId) {
            throw new ImportException('Missing {template} template.', ['{template}' => $template]);
        }
        $productId = $this->productQuery->findProductIdBySku($sku);
        $categories = $this->getCategories($categories);
        $attributes = $this->builder->build($attributes);
        $bindings = $this->getBindings($bindings, $sku);
        $children = $this->getChildren($sku, $children);

        if (!$productId) {
            $productId = ProductId::generate();
            /** @var VariableProduct $product */
            $product = $this->productFactory->create(
                VariableProduct::TYPE,
                $productId,
                $sku,
                $templateId,
                $categories,
                $attributes,
            );
        } else {
            $product = $this->productRepository->load($productId);
            if (!$product instanceof VariableProduct) {
                throw new ImportException('Product {sku} is not a variable product', ['{sku}' => $sku]);
            }
            $product->changeTemplate($templateId);
            $product->changeCategories($categories);
            $attributes = $this->mergeSystemAttributes($product->getAttributes(), $attributes);
            $product->changeAttributes($attributes);
        }

        $product->changeBindings($bindings);
        $product->changeChildren($children);

        $this->productRepository->save($product);

        return $product;
    }

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
}
