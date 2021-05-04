<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Importer\Domain\Command\Import\Attribute\ImportUpdateAttributesInProductCommand;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Importer\Infrastructure\Exception\ImportProductInProductRelationAttributeValueNotFoundException;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class SimpleProductImportAction extends AbstractProductImportAction
{
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
        array $attributes = [],
        ImportId $importId = null
    ): SimpleProduct {
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

        if (!$productId) {
            $product = $this->productFactory->create(
                SimpleProduct::TYPE,
                ProductId::generate(),
                $sku,
                $templateId,
                $categories,
                $attributesBuilt,
            );
        } else {
            $product = $this->productRepository->load($productId);
            if (!$product instanceof SimpleProduct) {
                throw new ImportException('Product {sku} is not a simple product', ['{sku}' => $sku]);
            }
            $product->changeTemplate($templateId);
            $attributesBuilt = $this->mergeSystemAttributes($product->getAttributes(), $attributesBuilt);
            $product->changeCategories($categories);
            $product->changeAttributes($attributesBuilt);
        }

        $this->productRepository->save($product);

        return $product;
    }
}
