<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Handler;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Product\Domain\Command\UpdateProductCommand;
use Webmozart\Assert\Assert;

/**
 */
class UpdateProductCommandHandler extends AbstractUpdateProductHandler
{
    /**
     * @param UpdateProductCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateProductCommand $command)
    {
        $product = $this->productRepository->load($command->getId());
        Assert::notNull($product);

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

        $product = $this->updateCategories($product, $command->getCategories());
        $product = $this->updateAudit($product);

        $this->productRepository->save($product);
    }
}
