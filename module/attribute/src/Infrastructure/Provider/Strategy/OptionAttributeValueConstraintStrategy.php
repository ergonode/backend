<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintStrategyInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class OptionAttributeValueConstraintStrategy implements AttributeValueConstraintStrategyInterface
{
    /**
     * @var OptionQueryInterface
     */
    private OptionQueryInterface $query;

    /**
     * @param OptionQueryInterface $query
     */
    public function __construct(OptionQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof AbstractOptionAttribute;
    }

    /**
     * @param AbstractAttribute|AbstractOptionAttribute $attribute
     *
     * @return Constraint
     */
    public function get(AbstractAttribute $attribute): Constraint
    {
        $multiple = $attribute instanceof MultiSelectAttribute;

        $choices = array_keys($this->query->getList($attribute->getId(), new Language('en')));

        return new Collection([
            'value' => [
                new Choice(['choices' => $choices, 'multiple' => $multiple]),
            ],
        ]);
    }
}
