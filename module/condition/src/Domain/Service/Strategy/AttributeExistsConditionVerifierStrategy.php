<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Service\Strategy;

use Ergonode\Condition\Domain\Condition\AttributeExistsCondition;
use Ergonode\Condition\Domain\Condition\ConditionInterface;
use Ergonode\Condition\Domain\Service\ConditionVerifierStrategyInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
class AttributeExistsConditionVerifierStrategy implements ConditionVerifierStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function isSupportedBy(string $type): bool
    {
        return AttributeExistsCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function verify(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        return $object->hasAttribute($configuration->getCode());
    }
}
