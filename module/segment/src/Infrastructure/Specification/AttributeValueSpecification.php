<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Specification;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Segment\Domain\Specification\SegmentSpecificationInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeValueSpecification extends AbstractSpecification implements SegmentSpecificationInterface
{
    /**
     * @var AttributeCode
     *
     * @JMS\Type("Ergonode\Component\Attribute\Domain\ValueObject\AttributeCode")
     */
    private $code;

    /**
     * @var ValueInterface
     *
     * @JMS\Type("Ergonode\Component\Product\Domain\ValueObject\AbstractValue")
     */
    private $value;

    /**
     * @param AttributeCode  $code
     * @param ValueInterface $value
     */
    public function __construct(AttributeCode $code, ValueInterface $value)
    {
        $this->code = $code;
        $this->value = $value;
    }

    /**
     * @param AbstractProduct $product
     *
     * @return bool
     */
    public function isSatisfiedBy(AbstractProduct $product): bool
    {
        if ($product->hasAttribute($this->code)) {
            $value = $product->getAttribute($this->code);
            if ($value->getValue() === $this->value->getValue()) {
                return true;
            }
        }

        return false;
    }
}
