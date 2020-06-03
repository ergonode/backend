<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Handler\Update;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Command\Update\UpdateVariableProductCommand;
use Ergonode\Product\Infrastructure\Handler\AbstractUpdateProductHandler;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;

/**
 */
class UpdateVariableProductCommandHandler extends AbstractUpdateProductHandler
{
    /**
     * @param UpdateVariableProductCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateVariableProductCommand $command)
    {
        /** @var VariableProduct $product */
        $product = $this->productRepository->load($command->getId());
        Assert::isInstanceOf($product, VariableProduct::class);

        foreach ($command->getAttributes() as $code => $attribute) {
            $attributeCode = new AttributeCode($code);
            if ($product->hasAttribute($attributeCode)) {
                $product->changeAttribute($attributeCode, $attribute);
            } else {
                $product->addAttribute($attributeCode, $attribute);
            }
        }

        foreach ($product->getAttributes() as $code => $value) {
            $attributeCode = new AttributeCode($code);
            if (!array_key_exists($code, $command->getAttributes())) {
                $product->removeAttribute($attributeCode);
            }
        }

        foreach ($command->getBindings() as $attributeId) {
            if (!$product->hasBind($attributeId)) {
                $attribute = $this->attributeRepository->load($attributeId);
                Assert::isInstanceOf($attribute, SelectAttribute::class);
                $product->addBind($attribute);
            }
        }

        $bindings  = [];
        foreach ($command->getBindings() as $binding) {
            $bindings[$binding->getValue()] = $binding;
        }

        foreach ($product->getBindings() as $attributeId) {
            if (!array_key_exists($attributeId->getValue(), $bindings)) {
                $product->removeBind($attributeId);
            }
        }

        $product = $this->updateCategories($product, $command->getCategories());
        $product = $this->updateAudit($product);

        $this->productRepository->save($product);
    }
}
