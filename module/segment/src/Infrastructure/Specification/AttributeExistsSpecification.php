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
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeExistsSpecification extends AbstractSpecification implements SegmentSpecificationInterface
{
    /**
     * @var AttributeCode
     *
     * @JMS\Type("Ergonode\Component\Attribute\Domain\ValueObject\AttributeCode")
     */
    private $code;

    /**
     * @param AttributeCode $code
     */
    public function __construct(AttributeCode $code)
    {
        $this->code = $code;
    }

    /**
     * @param AbstractProduct $product
     *
     * @return bool
     */
    public function isSatisfiedBy(AbstractProduct $product): bool
    {
        return $product->hasAttribute($this->code);
    }
}
