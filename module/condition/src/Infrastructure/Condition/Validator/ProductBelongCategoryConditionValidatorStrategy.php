<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Validator;

use Ergonode\Category\Application\Validator\CategoryExists;
use Ergonode\Condition\Domain\Condition\ProductBelongCategoryCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionValidatorStrategyInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

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

        return new Assert\Collection(
            [
                'category' => [
                    new Assert\NotBlank(),
                    new Assert\Type('array'),
                    new Assert\Count(['min' => 1]),
                    new Assert\All(
                        [
                            new CategoryExists(),
                        ]
                    ),
                ],
                'operator' => [
                    new Assert\NotBlank(),
                    new Assert\Choice(
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
