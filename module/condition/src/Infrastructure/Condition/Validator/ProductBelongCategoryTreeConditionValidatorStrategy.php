<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Validator;

use Ergonode\Category\Application\Validator\CategoryTreeExists;
use Ergonode\Condition\Domain\Condition\ProductBelongCategoryTreeCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionValidatorStrategyInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

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
        return new Assert\Collection(
            [
                'tree' => [
                    new Assert\NotBlank(),
                    new Assert\Type('array'),
                    new Assert\Count(['min' => 1]),
                    new Assert\All(
                        [
                            new CategoryTreeExists(),
                        ]
                    ),
                ],
                'operator' => [
                    new Assert\NotBlank(),
                    new Assert\Choice(
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
