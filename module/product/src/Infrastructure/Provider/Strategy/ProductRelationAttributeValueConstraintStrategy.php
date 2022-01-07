<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Infrastructure\Provider\ContextAwareAttributeValueConstraintStrategyInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Application\Validator\NotTheSameProduct;
use Ergonode\SharedKernel\Domain\AggregateId;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Ergonode\Product\Application\Validator\ProductExists;
use Symfony\Component\Validator\Constraints\All;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Count;

class ProductRelationAttributeValueConstraintStrategy implements ContextAwareAttributeValueConstraintStrategyInterface
{
    private int $max;

    public function __construct(int $max = ProductRelationAttribute::MAX_RELATIONS)
    {
        $this->max = $max;
    }

    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof ProductRelationAttribute;
    }

    public function get(
        AbstractAttribute $attribute,
        ?AggregateId $aggregateId = null,
        ?Language $language = null
    ): Constraint {
        $constraints = [
            new NotBlank(),
            new Uuid(['strict' => true]),
            new ProductExists(),
        ];

        if ($aggregateId) {
            $constraints[] = new NotTheSameProduct(['aggregateId' => $aggregateId]);
        }

        return new Collection([
            'value' => [
                new Count(['max' => $this->max]),
                new All(
                    ['constraints' => $constraints]
                ),
            ],
        ]);
    }
}
