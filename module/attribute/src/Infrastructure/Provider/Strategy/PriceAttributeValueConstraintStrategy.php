<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintStrategyInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Application\Validator\TypeOrEmpty;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class PriceAttributeValueConstraintStrategy implements AttributeValueConstraintStrategyInterface
{
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof PriceAttribute;
    }

    /**
     * @param AbstractAttribute|PriceAttribute $attribute
     */
    public function get(AbstractAttribute $attribute): Constraint
    {
        return new Collection([
            'value' => [
                new TypeOrEmpty(['type' => 'numeric']),
                new PositiveOrZero(),
            ],
        ]);
    }
}
