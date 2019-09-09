<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Service\Strategy;

use Ergonode\Condition\Domain\Service\SegmentVerifierStrategyInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Condition\Domain\Condition\ConditionInterface;
use Ergonode\Condition\Domain\Condition\AttributeExistsCondition;

/**
 */
class AttributeExistsConditionVerifierStrategy implements SegmentVerifierStrategyInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function isSupportedBy(string $type): bool
    {
        return AttributeExistsCondition::TYPE === $type;
    }

    /**
     * @param AbstractProduct                             $object
     * @param AttributeExistsCondition|ConditionInterface $configuration
     *
     * @return bool
     */
    public function verify(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        return $object->hasAttribute($configuration->getCode());
    }
}
