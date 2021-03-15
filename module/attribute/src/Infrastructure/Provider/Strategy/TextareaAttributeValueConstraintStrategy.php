<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;
use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintStrategyInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;

class TextareaAttributeValueConstraintStrategy implements AttributeValueConstraintStrategyInterface
{
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof TextareaAttribute;
    }

    public function get(AbstractAttribute $attribute): Constraint
    {
        return new Collection([
            'value' => [],
        ]);
    }
}
