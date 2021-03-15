<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Product\Domain\Query\GetProductQueryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Webmozart\Assert\Assert;

class GetProductQuery implements GetProductQueryInterface
{
    private ProductRepositoryInterface $repository;

    private UrlGeneratorInterface $router;

    public function __construct(ProductRepositoryInterface $repository, UrlGeneratorInterface $router)
    {
        $this->repository = $repository;
        $this->router = $router;
    }

    /**
     * @return array
     */
    public function query(ProductId $productId, Language $language): array
    {
        $product = $this->repository->load($productId);
        Assert::notNull($product);
        $attributes = [];
        foreach ($product->getAttributes() as $key => $attribute) {
            $value = $attribute->getValue();
            if ($value instanceof TranslatableString) {
                $value = $value->getTranslations();
            }
            $attributes[$key] = $value;
        }

        return [
            'id' => $product->getId(),
            'sku' => $product->getSku(),
            'type' => $product->getType(),
            'attributes' => $attributes,
            'categories' => $product->getCategories(),
            'template_id' => $product->getTemplateId()->getValue(),
            //@todo [legacy] remove after changes on frontend application
            'design_template_id' => $product->getTemplateId()->getValue(),
            '_links' => [
                'edit' => [
                    'href' =>  $this->router->generate(
                        'ergonode_product_read',
                        [ 'product' => $productId->getValue(), 'language' => $language->getCode()]
                    ),
                    'method' => Request::METHOD_PUT,
                ],
                'delete' => [
                    'href' => $this->router->generate(
                        'ergonode_product_delete',
                        [ 'product' => $productId->getValue(), 'language' => $language->getCode()]
                    ),
                    'method' => Request::METHOD_DELETE,
                ],
            ],
        ];
    }
}
