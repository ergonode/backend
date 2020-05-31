<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Handler;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Product\Domain\Entity\Attribute\EditedBySystemAttribute;
use Ergonode\Product\Domain\Entity\Attribute\EditedAtSystemAttribute;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

/**
 */
abstract class AbstractUpdateProductHandler
{
    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @var TokenStorageInterface
     */
    protected TokenStorageInterface $tokenStorage;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param TokenStorageInterface      $tokenStorage
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        TokenStorageInterface $tokenStorage
    ) {
        $this->productRepository = $productRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param AbstractProduct $product
     * @param array           $categories
     *
     * @return AbstractProduct
     *
     * @throws \Exception
     */
    public function updateCategories(AbstractProduct $product, array $categories): AbstractProduct
    {
        foreach ($categories as $categoryId) {
            if (!$product->belongToCategory($categoryId)) {
                $product->addToCategory($categoryId);
            }
        }

        foreach ($product->getCategories() as $categoryId) {
            if (!in_array($categoryId->getValue(), $categories, false)) {
                $product->removeFromCategory($categoryId);
            }
        }

        return $product;
    }

    /**
     * @param AbstractProduct $product
     *
     * @return AbstractProduct
     *
     * @throws \Exception
     */
    public function updateAudit(AbstractProduct $product): AbstractProduct
    {
        $token = $this->tokenStorage->getToken();
        if ($token) {
            $editedByCode = new AttributeCode(EditedBySystemAttribute::CODE);
            /** @var User $user */
            $user = $token->getUser();
            $editedByValue = new StringValue(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));
            $this->attributeUpdate($product, $editedByCode, $editedByValue);
        }

        return $product;
    }

    /**
     * @param AbstractProduct $product
     * @param AttributeCode   $code
     * @param ValueInterface  $value
     *
     * @throws \Exception
     */
    protected function attributeUpdate(AbstractProduct $product, AttributeCode $code, ValueInterface $value): void
    {
        if (!$product->hasAttribute($code)) {
            $product->addAttribute($code, $value);
        } else {
            $product->changeAttribute($code, $value);
        }
    }
}
