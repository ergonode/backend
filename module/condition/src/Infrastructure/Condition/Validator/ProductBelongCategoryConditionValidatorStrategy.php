<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

namespace Ergonode\Condition\Infrastructure\Condition\Validator;

use Ergonode\Category\Infrastructure\Validator\CategoryExists;
use Ergonode\Condition\Domain\Condition\ProductBelongCategoryCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionValidatorStrategyInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 */
class ProductBelongCategoryConditionValidatorStrategy implements ConditionValidatorStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return $type === ProductBelongCategoryCondition::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function build(array $data): Constraint
    {

        return new Collection(
            [
                'category' => [
                    new NotBlank(),
                    new CategoryExists(),
                ],
                'operator' => [
                    new NotBlank(),
                    new Choice(
                        [
                            ProductBelongCategoryCondition::BELONG_TO,
                            ProductBelongCategoryCondition::NOT_BELONG_TO,
                        ]
                    ),
                ],
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getValidatedClass(): string
    {
        return ProductBelongCategoryCondition::class;
    }
}
