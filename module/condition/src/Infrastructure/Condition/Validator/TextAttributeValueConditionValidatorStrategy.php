<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Condition\Validator;

use Ergonode\Attribute\Infrastructure\Validator\AttributeExists;
use Ergonode\Condition\Domain\Condition\TextAttributeValueCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionValidatorStrategyInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Ergonode\Attribute\Infrastructure\Validator\AttributeTypeValid;
use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;

/**
 */
class TextAttributeValueConditionValidatorStrategy implements ConditionValidatorStrategyInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supports(string $type): bool
    {
        return $type === TextAttributeValueCondition::TYPE;
    }

    /**
     * @param array $data
     *
     * @return Constraint
     */
    public function build(array $data): Constraint
    {
        return new Collection(
            [
                'attribute' => [
                    new NotBlank(),
                    new AttributeExists(),
                    new AttributeTypeValid(['type' => TextAttribute::TYPE]),
                ],
                'operator' => [
                    new NotBlank(),
                    new Choice(['=', '~']),
                ],
                'value' => [
                    new NotBlank(),
                ],
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getValidatedClass(): string
    {
        return TextAttributeValueCondition::class;
    }
}
