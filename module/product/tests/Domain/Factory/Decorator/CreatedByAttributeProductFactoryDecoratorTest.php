<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Tests\Domain\Factory\Decorator;

use Ergonode\Account\Domain\Entity\User;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Product\Domain\Entity\Attribute\CreatedBySystemAttribute;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Factory\Decorator\CreatedByAttributeProductFactoryDecorator;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 */
class CreatedByAttributeProductFactoryDecoratorTest extends TestCase
{
    /**
     */
    public function testCreateMethod(): void
    {
        /** @var ProductFactoryInterface| MockObject $factory */
        $factory = $this->createMock(ProductFactoryInterface::class);
        $factory->expects($this->once())
            ->method('create')
            ->with(
                $this->anything(),
                $this->anything(),
                $this->anything(),
                $this->arrayHasKey(CreatedBySystemAttribute::CODE)
            );

        /** @var User | MockObject $user */
        $user = $this->createMock(User::class);
        $user->method('getFirstName')->willReturn('First_name');
        $user->method('getLastName')->willReturn('Last_name');

        /** @var TokenInterface | MockObject $tokenInteface */
        $tokenInteface = $this->createMock(TokenInterface::class);
        $tokenInteface->method('getUser')->willReturn($user);

        /** @var TokenStorageInterface | MockObject $tokenStorage */
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->method('getToken')->willReturn($tokenInteface);

        /** @var ProductId | MockObject $productId */
        $productId = $this->createMock(ProductId::class);

        /** @var Sku | MockObject $sku */
        $sku = $this->createMock(Sku::class);

        $categories = [$this->createMock(CategoryCode::class)];
        $attributes = [$this->createMock(ValueInterface::class)];

        $decorator = new CreatedByAttributeProductFactoryDecorator($factory, $tokenStorage);

        $decorator->create($productId, $sku, $categories, $attributes);
    }
}
