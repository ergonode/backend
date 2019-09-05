<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Specification;

use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
interface SegmentSpecificationInterface
{
    /**
     * @param AbstractProduct $domainProduct
     *
     * @return bool
     */
    public function isSatisfiedBy(AbstractProduct $domainProduct): bool;
}
