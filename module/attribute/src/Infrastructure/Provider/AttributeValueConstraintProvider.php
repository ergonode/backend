<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Provider;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Symfony\Component\Validator\Constraint;

/**
 */
class AttributeValueConstraintProvider
{
    /**
     * @var AttributeValueConstraintStrategyInterface[]
     */
    private array $strategies;

    /**
     * @param AttributeValueConstraintStrategyInterface ...$strategies
     */
    public function __construct(AttributeValueConstraintStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param AbstractAttribute $attribute
     *
     * @return Constraint
     */
    public function provide(AbstractAttribute $attribute): Constraint
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($attribute)) {
                return $strategy->get($attribute);
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find validation for %s attribute type', $attribute->getType()));
    }
}
