<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintStrategyInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 */
class StatusAttributeValueConstraintStrategy implements AttributeValueConstraintStrategyInterface
{
    /**
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof StatusSystemAttribute;
    }

    /**
     * @param AbstractAttribute|PriceAttribute $attribute
     *
     * @return Constraint
     */
    public function get(AbstractAttribute $attribute): Constraint
    {
        return new Collection([
            'value' => [
                new NotBlank(['message' => 'Status Must be set']),
                new Length(['max' => 255]),
            ],
        ]);
    }
}
