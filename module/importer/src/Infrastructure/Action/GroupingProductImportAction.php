<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Product\Domain\Entity\GroupingProduct;

class GroupingProductImportAction extends AbstractProductImportAction
{
    public function action(
        Sku $sku,
        string $template,
        array $categories,
        array $children,
        array $attributes = []
    ): GroupingProduct {
        $templateId = $this->templateQuery->findTemplateIdByCode($template);
        if (null === $templateId) {
            throw new ImportException('Missing {template} template.', ['{template}' => $template]);
        }
        $productId = $this->productQuery->findProductIdBySku($sku);
        $categories = $this->getCategories($categories);
        $attributes = $this->builder->build($attributes);
        $children = $this->getChildren($sku, $children);

        if (!$productId) {
            $productId = ProductId::generate();
            $product = $this->productFactory->create(
                GroupingProduct::TYPE,
                $productId,
                $sku,
                $templateId,
                $categories,
                $attributes,
            );
        } else {
            $product = $this->productRepository->load($productId);
            if (!$product instanceof GroupingProduct) {
                throw new ImportException('Product {sku} is not a grouping product', ['{sku}' => $sku]);
            }
            $product->changeTemplate($templateId);
            $product->changeCategories($categories);
            $attributes = $this->mergeSystemAttributes($product->getAttributes(), $attributes);
            $product->changeAttributes($attributes);
            $product->changeChildren($children);
        }

        $this->productRepository->save($product);

        return $product;
    }
}
