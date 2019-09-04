<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Service\Condition;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Segment\Domain\Condition\ConditionInterface;
use Ergonode\Segment\Domain\Specification\AttributeExistsCondition;
use Webmozart\Assert\Assert;

/**
 */
class AttributeExistsConditionVerifierStrategy
{
    /**
     * @param AbstractProduct                             $object
     * @param AttributeExistsCondition|ConditionInterface $configuration
     *
     * @return bool
     */
    public function verify(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        Assert::isInstanceOf($configuration, AttributeExistsCondition::class);

        return $object->hasAttribute($configuration->getCode());
    }
}
