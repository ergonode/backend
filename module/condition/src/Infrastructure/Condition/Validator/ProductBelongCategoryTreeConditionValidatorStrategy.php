<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Condition\Validator;

use Ergonode\Category\Infrastructure\Validator\CategoryTreeExists;
use Ergonode\Condition\Domain\Condition\ProductBelongCategoryTreeCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionValidatorStrategyInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 */
class ProductBelongCategoryTreeConditionValidatorStrategy implements ConditionValidatorStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return $type === ProductBelongCategoryTreeCondition::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function build(array $data): Constraint
    {
        return new Collection(
            [
                'tree' => [
                    new NotBlank(),
                    new CategoryTreeExists(),
                ],
                'operator' => [
                    new NotBlank(),
                    new Choice(
                        [
                            ProductBelongCategoryTreeCondition::BELONG_TO,
                            ProductBelongCategoryTreeCondition::NOT_BELONG_TO,
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
        return ProductBelongCategoryTreeCondition::class;
    }
}
