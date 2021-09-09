<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Provider;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

class ProductStatusProvider
{
    private ProductRepositoryInterface $repository;

    public function __construct(ProductRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getProduct(
        AbstractProduct $product,
        AbstractWorkflow $workflow,
        Language $language
    ): AbstractProduct {
        $code = new AttributeCode(StatusSystemAttribute::CODE);

        $value = [];
        if ($product->hasAttribute($code)) {
            $value = $product->getAttribute($code)->getValue();
        }

        if (!array_key_exists($language->getCode(), $value)) {
            $status = $workflow->getDefaultStatus();
            $value[$language->getCode()] = $status->getValue();
            $attribute = new TranslatableStringValue(new TranslatableString($value));
            if ($product->hasAttribute($code)) {
                $product->changeAttribute($code, $attribute);
            } else {
                $product->addAttribute($code, $attribute);
            }
            $this->repository->save($product);
        }

        return $product;
    }
}
