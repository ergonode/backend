<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\Attribute\Application\Validator\TypeOrEmpty;
use Ergonode\Attribute\Infrastructure\Provider\ContextAwareAttributeValueConstraintStrategyInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;

class NumericAttributeValueConstraintStrategy implements ContextAwareAttributeValueConstraintStrategyInterface
{
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof NumericAttribute;
    }

    public function get(AbstractAttribute $attribute, AggregateId $aggregateId = null): Constraint
    {
        return new Collection([
            'value' => [
                new TypeOrEmpty(['type' => 'numeric']),
            ],
        ]);
    }
}
