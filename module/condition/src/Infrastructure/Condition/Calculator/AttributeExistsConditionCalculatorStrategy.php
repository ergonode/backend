<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Condition\Domain\Condition\AttributeExistsCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Webmozart\Assert\Assert;

class AttributeExistsConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    private AttributeRepositoryInterface $repository;

    public function __construct(AttributeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return AttributeExistsCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function calculate(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        if (!$configuration instanceof AttributeExistsCondition) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    AttributeExistsCondition::class,
                    get_debug_type($configuration)
                )
            );
        }
        $attributeId = $configuration->getAttribute();
        $attribute = $this->repository->load($attributeId);
        Assert::notNull($attribute);

        return $object->hasAttribute($attribute->getCode());
    }
}
