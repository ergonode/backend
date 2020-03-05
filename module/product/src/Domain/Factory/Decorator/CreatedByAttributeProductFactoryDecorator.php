<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Factory\Decorator;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\Attribute\CreatedBySystemAttribute;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 */
class CreatedByAttributeProductFactoryDecorator implements ProductFactoryInterface
{
    /**
     * @var ProductFactoryInterface
     */
    private ProductFactoryInterface $factory;

    /**
     * @var TokenStorageInterface
     */
    private TokenStorageInterface $tokenStorage;

    /**
     * @param ProductFactoryInterface $factory
     * @param TokenStorageInterface   $tokenStorage
     */
    public function __construct(ProductFactoryInterface $factory, TokenStorageInterface $tokenStorage)
    {
        $this->factory = $factory;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function isSupportedBy(string $type): bool
    {
        return $this->factory->isSupportedBy($type);
    }

    /**
     * @param ProductId $id
     * @param Sku       $sku
     * @param array     $categories
     * @param array     $attributes
     *
     * @return AbstractProduct
     *
     * @throws \Exception
     */
    public function create(
        ProductId $id,
        Sku $sku,
        array $categories = [],
        array $attributes = []
    ): AbstractProduct {
        $token = $this->tokenStorage->getToken();
        if ($token) {
            /** @var User $user */
            $user = $token->getUser();
            $value = new StringValue(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));
            $attributes[CreatedBySystemAttribute::CODE] = $value;
        }

        return $this->factory->create($id, $sku, $categories, $attributes);
    }
}
