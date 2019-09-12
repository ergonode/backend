<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Builder\Condition;

use Ergonode\Attribute\Infrastructure\Validator\AttributeExists;
use Ergonode\Condition\Infrastructure\Builder\ConditionValidatorBuilderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 */
class TextAttributeValueConditionValidatorBuilder implements ConditionValidatorBuilderInterface
{
    /**
     * @param array $data
     *
     * @return Constraint
     */
    public function build(array $data): Constraint
    {
        return new Collection([
                'attribute' => [
                    new NotBlank(),
                    new AttributeExists(),
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
}
