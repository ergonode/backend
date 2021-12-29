<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Validator;

use Ergonode\Attribute\Application\Validator\AttributeExists;
use Ergonode\Condition\Infrastructure\Condition\ConditionValidatorStrategyInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Ergonode\Core\Application\Validator\LanguageCodeActive;
use Ergonode\Condition\Domain\Condition\AttributeTranslationExistsCondition;

class AttributeTranslationExistsConditionValidatorStrategy implements ConditionValidatorStrategyInterface
{
    public function supports(string $type): bool
    {
        return $type === AttributeTranslationExistsCondition::TYPE;
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
                ],
                'language' => [
                    new NotBlank(),
                    new LanguageCodeActive(),
                ],
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getValidatedClass(): string
    {
        return AttributeTranslationExistsCondition::class;
    }
}
