<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler\Binding;

use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Command\Bindings\RemoveProductBindingCommand;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;

class RemoveProductBindCommandHandler
{
    private AttributeRepositoryInterface $attributeRepository;

    private ProductRepositoryInterface $productRepository;

    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(RemoveProductBindingCommand $command): void
    {
        /** @var VariableProduct $product */
        $product = $this->productRepository->load($command->getId());
        /** @var SelectAttribute $attribute */
        $attribute = $this->attributeRepository->load($command->getBindingId());
        Assert::isInstanceOf(
            $product,
            VariableProduct::class,
            sprintf('Can\'t find variable product with id "%s"', $command->getId())
        );

        Assert::isInstanceOf(
            $attribute,
            SelectAttribute::class,
            sprintf('Can\'t find select attribute with id "%s"', $command->getId())
        );

        $product->removeBind($attribute->getId());

        $this->productRepository->save($product);
    }
}
