<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Validator;

use Ergonode\Condition\Domain\Condition\ProductHasTemplateCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionValidatorStrategyInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Designer\Application\Validator\TemplateExists;

class ProductHasTemplateConditionValidatorStrategy implements ConditionValidatorStrategyInterface
{

    public function supports(string $type): bool
    {
        return $type ===  ProductHasTemplateCondition::TYPE;
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
                    new Assert\Choice(ProductHasTemplateCondition::getSupportedOperators()),
                ],
                'template_id' => [
                    new TemplateExists(),
                    new Assert\NotBlank(),
                ],
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getValidatedClass(): string
    {
        return ProductHasTemplateCondition::class;
    }
}
