<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\ValueObject\Sku;
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
            $product = $this->productRepository->load($productId);
            if (!$product instanceof SimpleProduct) {
                throw new ImportException('Product {sku} is not a simple product', ['{sku}' => $sku]);
            }
            $product->changeTemplate($templateId);
            $attributes = $this->mergeSystemAttributes($product->getAttributes(), $attributes);
            $product->changeCategories($categories);
            $product->changeAttributes($attributes);
        }

        $this->productRepository->save($product);

        return $product;
    }
}
