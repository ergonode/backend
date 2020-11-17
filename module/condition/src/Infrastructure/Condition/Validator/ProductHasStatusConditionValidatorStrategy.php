<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Validator;

use Ergonode\Condition\Domain\Condition\ProductHasStatusCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionValidatorStrategyInterface;
use Ergonode\Core\Infrastructure\Validator\Constraint\LanguageCodeExists;
use Ergonode\Workflow\Infrastructure\Validator\StatusIdNotExists;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class ProductHasStatusConditionValidatorStrategy implements ConditionValidatorStrategyInterface
{

    public function supports(string $type): bool
    {
        return $type === ProductHasStatusCondition::TYPE;
    }

    /**
     * @param array $data
     */
    public function build(array $data): Constraint
    {
        return new Assert\Collection(
            [
                'operator' => [
                    new Assert\NotBlank(),
                    new Assert\Choice(ProductHasStatusCondition::getSupportedOperators()),
                ],
                'value' => [
                    new Assert\NotBlank(),
                    new Assert\Type('array'),
                    new Assert\Count(['min' => 1]),
                    new Assert\All([
                        new StatusIdNotExists(),
                    ]),
                ],
                'language' => [
                    new Assert\NotBlank(),
                    new Assert\Type('array'),
                    new Assert\Count(['min' => 1]),
                    new Assert\All([
                        new LanguageCodeExists(),
                    ]),
                ],
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getValidatedClass(): string
    {
        return ProductHasStatusCondition::class;
    }
}
