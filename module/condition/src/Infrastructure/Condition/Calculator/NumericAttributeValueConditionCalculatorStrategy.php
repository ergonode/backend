<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Condition\Domain\Condition\NumericAttributeValueCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Webmozart\Assert\Assert;

/**
 */
class NumericAttributeValueConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    private $repository;

    /**
     * @param AttributeRepositoryInterface $repository
     */
    public function __construct(AttributeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return NumericAttributeValueCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function calculate(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        $attributeId = $configuration->getAttribute();
        $attribute = $this->repository->load($attributeId);
        Assert::notNull($attribute);

        $option = $configuration->getOption();
        $expected = $configuration->getValue();

        if ($object->hasAttribute($attribute->getCode())) {
            $value = (float) $object->getAttribute($attribute->getCode())->getValue();
            if (('=' === $option) && $value !== $expected) {
                return false;
            }

            if (('<>' === $option) && $value === $expected) {
                return false;
            }

            if (('>' === $option) && $value <= $expected) {
                return false;
            }

            if (('>=' === $option) && $value < $expected) {
                return false;
            }

            if (('<' === $option) && $value >= $expected) {
                return false;
            }

            if (('<=' === $option) && $value > $expected) {
                return false;
            }
        }

        return true;
    }
}
