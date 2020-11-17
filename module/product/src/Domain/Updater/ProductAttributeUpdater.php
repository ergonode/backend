<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Updater;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Infrastructure\Mapper\AttributeValueMapper;
use Ergonode\Value\Domain\Service\ValueManipulationService;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;

class ProductAttributeUpdater
{
    private AttributeValueMapper $mapper;

    private ValueManipulationService $service;

    public function __construct(AttributeValueMapper $mapper, ValueManipulationService $service)
    {
        $this->mapper = $mapper;
        $this->service = $service;
    }

    public function update(AbstractProduct $product, AbstractAttribute $attribute, array $value): AbstractProduct
    {
        $type = new AttributeType($attribute->getType());
        $code = $attribute->getCode();

        $newValue = $this->mapper->map($type, $value);

        if ($product->hasAttribute($code)) {
            if (null === $newValue) {
                $product->removeAttribute($code);
            } else {
                $oldValue = $product->getAttribute($code);
                $calculatedValue = $this->service->calculate($oldValue, $newValue);
                $product->changeAttribute($code, $calculatedValue);
            }
        } else {
            $product->addAttribute($code, $newValue);
        }

        return $product;
    }
}
