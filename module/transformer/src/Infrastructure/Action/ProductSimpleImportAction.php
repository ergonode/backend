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
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Transformer\Infrastructure\Action\Builder\ProductImportBuilderInterface;
use Ergonode\Transformer\Infrastructure\Exception\ProcessorException;
use Ergonode\Value\Domain\ValueObject\StringValue;
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
    private TemplateProvider $templateProvider;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $productQuery;

    /**
     * @var ProductFactoryProvider
     */
    private ProductFactoryProvider $productFactoryProvider;

    /**
     * @var ProductImportBuilderInterface ...$builders
     */
    private array $builders;

    /**
     * @param TemplateProvider                      $templateProvider
     * @param ProductRepositoryInterface            $productRepository
     * @param ProductQueryInterface                 $productQuery
     * @param ProductFactoryProvider                $productFactoryProvider
     * @param array|ProductImportBuilderInterface[] $builders
     */
    public function __construct(
        TemplateProvider $templateProvider,
        ProductRepositoryInterface $productRepository,
        ProductQueryInterface $productQuery,
        ProductFactoryProvider $productFactoryProvider,
        $builders
    ) {
        $this->templateProvider = $templateProvider;
        $this->productRepository = $productRepository;
        $this->productQuery = $productQuery;
        $this->productFactoryProvider = $productFactoryProvider;
        $this->builders = $builders;
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

        $sku = $record->get('sku') ? new Sku($record->get('sku')) : null;
        Assert::notNull($sku, 'product import required "sku" field not exists');
        $productData = $this->productQuery->findBySku($sku);

        if (!$productData) {
//            if ($record->has('template') && null !== $record->get('template')) {
//                $template = $this->templateProvider->provide($record->get('template'));
//            } else {
                $template = $this->templateProvider->provide(DefaultTemplateGenerator::CODE);
//            }

            $data['attributes']['esa_template'] = new StringValue($template->getId()->getValue());

            $product = $this->productFactoryProvider->provide(SimpleProduct::TYPE)->create(
                ProductId::generate(),
                $sku,
                $data['categories'],
                $data['attributes']
            );
        } else {
            $product = $this->productRepository->load(new ProductId($productData['id']));
            if (!$product) {
                throw new \RuntimeException(sprintf('Can\'t find product "%s"', $sku->getValue()));
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
