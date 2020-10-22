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
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;

abstract class AbstractUpdateProductHandler
{
    protected ProductRepositoryInterface $productRepository;

    protected AttributeRepositoryInterface $attributeRepository;

    protected TokenStorageInterface $tokenStorage;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        AttributeRepositoryInterface $attributeRepository,
        TokenStorageInterface $tokenStorage
    ) {
        $this->productRepository = $productRepository;
        $this->attributeRepository = $attributeRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
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
