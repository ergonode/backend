<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Attribute\Application\Validator\TypeOrEmpty;
use Ergonode\Attribute\Infrastructure\Provider\ContextAwareAttributeValueConstraintStrategyInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;

class UnitAttributeValueConstraintStrategy implements ContextAwareAttributeValueConstraintStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof UnitAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function get(AbstractAttribute $attribute, $aggregateId = null): Constraint
    {
        return new Collection([
            'value' => [
                new TypeOrEmpty(['type' => 'numeric']),
            ],
        ]);
    }
}
