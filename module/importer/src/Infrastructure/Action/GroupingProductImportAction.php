<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Importer\Domain\Command\Import\Attribute\ImportUpdateAttributesInProductCommand;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Infrastructure\Exception\ImportProductInProductRelationAttributeValueNotFoundException;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
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
        array $attributes = [],
        ImportId $importId = null
    ): GroupingProduct {
        $templateId = $this->templateQuery->findTemplateIdByCode($template);
        if (null === $templateId) {
            throw new ImportException('Missing {template} template.', ['{template}' => $template]);
        }
        $productId = $this->productQuery->findProductIdBySku($sku);
        $categories = $this->getCategories($categories);
        try {
            $attributesBuilt = $this->builder->build($attributes);
        } catch (ImportProductInProductRelationAttributeValueNotFoundException $e) {
            if ($importId) {
                $command = new ImportUpdateAttributesInProductCommand($importId, $attributes, $sku);
                $this->commandBus->dispatch($command, true);
            }
            $attributesBuilt = [];
        }
        $children = $this->getChildren($sku, $children);

        if (!$productId) {
            $productId = ProductId::generate();
            $product = $this->productFactory->create(
                GroupingProduct::TYPE,
                $productId,
                $sku,
                $templateId,
                $categories,
                $attributesBuilt,
            );
        } else {
            $product = $this->productRepository->load($productId);
            if (!$product instanceof GroupingProduct) {
                throw new ImportException('Product {sku} is not a grouping product', ['{sku}' => $sku]);
            }
            $product->changeTemplate($templateId);
            $product->changeCategories($categories);
            $attributesBuilt = $this->mergeSystemAttributes($product->getAttributes(), $attributesBuilt);
            $product->changeAttributes($attributesBuilt);
            $product->changeChildren($children);
        }

        $this->productRepository->save($product);

        return $product;
    }
}
