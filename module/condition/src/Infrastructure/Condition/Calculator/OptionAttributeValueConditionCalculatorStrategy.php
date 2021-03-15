<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Condition\Domain\Condition\OptionAttributeValueCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Webmozart\Assert\Assert;

class OptionAttributeValueConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
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
        return OptionAttributeValueCondition::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     *
     * @param OptionAttributeValueCondition $configuration
     */
    public function calculate(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        $attributeId = $configuration->getAttribute();
        /** @var AbstractOptionAttribute $attribute */
        $attribute = $this->repository->load($attributeId);
        Assert::notNull($attribute);

        $expected = $configuration->getValue();

        if ($object->hasAttribute($attribute->getCode())) {
            $values = $object->getAttribute($attribute->getCode())->getValue();
            foreach ($values as $value) {
                // exploding for multiselect values sake
                foreach (explode(',', $value) as $selected) {
                    if ($selected === $expected) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
