<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Validator;

use Ergonode\Attribute\Application\Validator\AttributeExists;
use Ergonode\Condition\Domain\Condition\NumericAttributeValueCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionValidatorStrategyInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\Attribute\Application\Validator\AttributeTypeValid;

class NumericAttributeValueConditionValidatorStrategy implements ConditionValidatorStrategyInterface
{
    public function supports(string $type): bool
    {
        return $type === NumericAttributeValueCondition::TYPE;
    }

    /**
     * @param array $data
     */
    public function build(array $data): Constraint
    {
        return new Collection(
            [
                'attribute' => [
                    new NotBlank(),
                    new AttributeExists(),
                    new AttributeTypeValid(['type' => NumericAttribute::TYPE]),
                ],
                'operator' => [
                    new NotBlank(),
                    new Choice(['=', '<>', '>', '<', '>=', '<=']),
                ],
                'value' => [
                    new NotBlank(),
                    new Type('numeric'),
                ],
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getValidatedClass(): string
    {
        return NumericAttributeValueCondition::class;
    }
}
