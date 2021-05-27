<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Infrastructure\Provider\ContextAwareAttributeValueConstraintStrategyInterface;
use Ergonode\EventSourcing\Infrastructure\Manager\EventStoreManagerInterface;
use Ergonode\Product\Application\Validator\NotTheSameProduct;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\AggregateId;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Ergonode\Product\Application\Validator\ProductExists;
use Symfony\Component\Validator\Constraints\All;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Constraints\NotBlank;
use Webmozart\Assert\Assert;

class ProductRelationAttributeValueConstraintStrategy implements ContextAwareAttributeValueConstraintStrategyInterface
{
    private EventStoreManagerInterface $manager;

    public function __construct(EventStoreManagerInterface $manager)
    {
        $this->manager = $manager;
    }


    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof ProductRelationAttribute;
    }

    public function get(AbstractAttribute $attribute, ?AggregateId $aggregateId = null): Constraint
    {
        Assert::notnull($aggregateId);
        $aggregate = $this->manager->load($aggregateId);
        Assert::isInstanceOf($aggregate, AbstractProduct::class);

        return new Collection([
            'value' => new All(
                ['constraints' =>
                    [
                        new NotBlank(),
                        new Uuid(['strict' => true]),
                        new ProductExists(),
                        new NotTheSameProduct(['aggregateId' => $aggregateId->getValue()]),
                    ],
                ]
            ),
        ]);
    }
}
