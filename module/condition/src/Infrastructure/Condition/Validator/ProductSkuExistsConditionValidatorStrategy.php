<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);


namespace Ergonode\Condition\Infrastructure\Condition\Validator;

use Ergonode\Condition\Domain\Condition\ProductSkuExistsCondition;
use Ergonode\Condition\Infrastructure\Condition\ConditionValidatorStrategyInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 */
class ProductSkuExistsConditionValidatorStrategy implements ConditionValidatorStrategyInterface
{
    /**
     * @inheritDoc
     */
    public function supports(string $type): bool
    {
        return $type === ProductSkuExistsCondition::TYPE;
    }

    /**
     * @inheritDoc
     */
    public function build(array $data): Constraint
    {
        // @todo regexp validation if selected REGEXP
        return new Collection(
            [
                'operator' => [
                    new NotBlank(),
                    new Choice(['=', '<>', '~', 'REGEXP']),
                ],
                'value' => [
                    new NotBlank(),
                ],
            ]
        );
    }
}
