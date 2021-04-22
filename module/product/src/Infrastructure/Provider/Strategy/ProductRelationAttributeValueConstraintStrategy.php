<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Infrastructure\Provider\AttributeValueConstraintStrategyInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Ergonode\Product\Application\Validator\ProductExists;
use Symfony\Component\Validator\Constraints\All;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductRelationAttributeValueConstraintStrategy implements AttributeValueConstraintStrategyInterface
{
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof ProductRelationAttribute;
    }

    public function get(AbstractAttribute $attribute): Constraint
    {
        return new Collection([
            'value' => new All(
                ['constraints' =>
                    [
                        new NotBlank(),
                        new Uuid(['strict' => true]),
                        new ProductExists(),
                    ],
                ]
            ),
        ]);
    }
}
