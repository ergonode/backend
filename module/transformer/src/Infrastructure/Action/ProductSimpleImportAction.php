<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Action;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Designer\Infrastructure\Generator\DefaultTemplateGenerator;
use Ergonode\Designer\Infrastructure\Provider\TemplateProvider;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Provider\ProductFactoryProvider;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\ProductSimple\Domain\Entity\SimpleProduct;
use Ergonode\Transformer\Infrastructure\Action\Extension\ProductAttributeExtension;
use Ergonode\Transformer\Infrastructure\Action\Extension\ProductCategoryExtension;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Transformer\Infrastructure\Exception\ProcessorException;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Webmozart\Assert\Assert;

/**
 */
class ProductSimpleImportAction implements ImportActionInterface
{
    public const TYPE = 'PRODUCT';

    /**
     * @var TemplateProvider
     */
    private $templateProvider;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ProductQueryInterface
     */
    private $productQuery;

    /**
     * @var ProductAttributeExtension
     */
    private $attributeExtension;

    /**
     * @var ProductCategoryExtension
     */
    private $categoryExtension;

    /**
     * @var ProductFactoryProvider
     */
    private $productFactoryProvider;

    /**
     * @param TemplateProvider           $templateProvider
     * @param ProductRepositoryInterface $productRepository
     * @param ProductQueryInterface      $productQuery
     * @param ProductAttributeExtension  $attributeExtension
     * @param ProductCategoryExtension   $categoryExtension
     * @param ProductFactoryProvider     $productFactoryProvider
     */
    public function __construct(
        TemplateProvider $templateProvider,
        ProductRepositoryInterface $productRepository,
        ProductQueryInterface $productQuery,
        ProductAttributeExtension $attributeExtension,
        ProductCategoryExtension $categoryExtension,
        ProductFactoryProvider $productFactoryProvider
    ) {
        $this->templateProvider = $templateProvider;
        $this->productRepository = $productRepository;
        $this->productQuery = $productQuery;
        $this->attributeExtension = $attributeExtension;
        $this->categoryExtension = $categoryExtension;
        $this->productFactoryProvider = $productFactoryProvider;
    }

    /**
     * @param Record $record
     *
     * @throws \Exception
     */
    public function action(Record $record): void
    {
        $data = [
            'attributes' => [],
            'categories' => [],
        ];

        $sku = $record->get('sku') ? new Sku($record->get('sku')->getValue()) : null;
        Assert::notNull($sku, 'product import required "sku" field not exists');
        $productData = $this->productQuery->findBySku($sku);

        $data = $this->categoryExtension->extend($record, $data);
        $data = $this->attributeExtension->extend($record, $data);

        if (!$productData) {
            if ($record->has('template') && null !== $record->get('template')) {
                $template = $this->templateProvider->provide($record->get('template')->getValue());
            } else {
                $template = $this->templateProvider->provide(DefaultTemplateGenerator::CODE);
            }

            $product = $this->productFactoryProvider->provide(SimpleProduct::TYPE)->create(
                ProductId::generate(),
                $sku,
                $template->getId(),
                $data['categories'],
                $data['attributes']
            );
        } else {
            $product = $this->productRepository->load(new ProductId($productData['id']));
            if (!$product) {
                throw new ProcessorException(sprintf('Can\'t find product "%s"', $sku->getValue()));
            }

            foreach ($data['attributes'] as $code => $value) {
                $attributeCode = new AttributeCode($code);
                $this->updateProduct($product, $attributeCode, $value);
            }

            foreach ($data['categories'] as $category) {
                $product->addToCategory($category);
            }

            foreach ($product->getCategories() as $category) {
                if (isset($categories[$category->getValue()])) {
                    $product->removeFromCategory($category);
                }
            }
        }

        $this->productRepository->save($product);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param AbstractProduct     $product
     * @param AttributeCode       $attributeCode
     * @param ValueInterface|null $value
     *
     * @throws \Exception
     */
    private function updateProduct(
        AbstractProduct $product,
        AttributeCode $attributeCode,
        ?ValueInterface $value = null
    ): void {
        if (null !== $value) {
            if (!$product->hasAttribute($attributeCode)) {
                $product->addAttribute($attributeCode, $value);
            } else {
                $product->changeAttribute($attributeCode, $value);
            }
        } elseif ($product->hasAttribute($attributeCode)) {
            $product->removeAttribute($attributeCode);
        }
    }
}
