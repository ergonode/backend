<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintStrategyInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Regex;

class DateAttributeValueConstraintStrategy implements AttributeValueConstraintStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof DateAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function get(AbstractAttribute $attribute): Constraint
    {
        return new Collection([
            'value' => [
                new Regex(['pattern' => '([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))']),
                new DateTime('Y-m-d'),
            ],
        ]);
    }
}
