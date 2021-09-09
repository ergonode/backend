<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Importer\Infrastructure\Action\Process\Product\ImportProductAttributeBuilder;
use Ergonode\Importer\Infrastructure\Exception\ImportException;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

class ImportProductAttributesAction extends AbstractProductImportAction
{

    private ImportProductAttributeBuilder $attributeBuilder;

    public function __construct(
        ImportProductAttributeBuilder $attributeBuilder,
        CategoryQueryInterface $categoryQuery,
        ProductQueryInterface $productQuery,
        ProductRepositoryInterface $productRepository,
        TemplateQueryInterface $templateQuery,
        ImportProductAttributeBuilder $builder,
        ProductFactoryInterface $productFactory,
        CommandBusInterface $commandBus
    ) {
        parent::__construct(
            $categoryQuery,
            $productQuery,
            $productRepository,
            $templateQuery,
            $builder,
            $productFactory,
            $commandBus
        );
        $this->attributeBuilder = $attributeBuilder;
    }

    /**
     * @param TranslatableString[] $attributes
     */
    public function action(Sku $sku, array $attributes): void
    {
        $attributesBuilt = $this->attributeBuilder->build($attributes);
        $productId = $this->productQuery->findProductIdBySku($sku);

        if (null === $productId) {
            throw new ImportException('Missing {sku} product.', ['{sku}' => $sku->getValue()]);
        }
        $product = $this->productRepository->load($productId);

        if (!$product) {
            throw new \RuntimeException(
                sprintf('Can\'t find product %s', $sku)
            );
        }
        foreach ($attributesBuilt as $codeValue => $attribute) {
            $code = new AttributeCode($codeValue);

            if ($product->hasAttribute($code)) {
                $product->changeAttribute($code, $attribute);
            } else {
                $product->addAttribute($code, $attribute);
            }
        }
        $this->productRepository->save($product);
    }
}
