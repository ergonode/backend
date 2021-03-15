<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintStrategyInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Symfony\Component\Validator\Constraints\Unique;

class OptionAttributeValueConstraintStrategy implements AttributeValueConstraintStrategyInterface
{
    private OptionQueryInterface $query;

    public function __construct(OptionQueryInterface $query)
    {
        $this->query = $query;
    }

    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof AbstractOptionAttribute;
    }

    /**
     * @param AbstractAttribute|AbstractOptionAttribute $attribute
     */
    public function get(AbstractAttribute $attribute): Constraint
    {
        $multiple = $attribute instanceof MultiSelectAttribute;

        $options = $this->query->getOptions($attribute->getId());
        $constrains = [
            'value' => [
                new Choice(['choices' => $options, 'multiple' => $multiple]),
            ],
        ];

        if ($multiple) {
            $constrains['value'][] = new Unique();
        }

        return new Collection($constrains);
    }
}
