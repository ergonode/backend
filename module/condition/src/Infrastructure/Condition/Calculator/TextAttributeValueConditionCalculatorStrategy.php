<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Condition\Domain\Condition\TextAttributeValueCondition;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Webmozart\Assert\Assert;

class TextAttributeValueConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

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
        return TextAttributeValueCondition::TYPE === $type;
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
            $value = $object->getAttribute($attribute->getCode())->getValue();

            if ('=' === $option) {
                return $this->calculateEqualTranslatableStringValue($value, $expected);
            }

            if ('~' === $option) {
                return $this->calculateHasTranslatableStringValue($value, $expected);
            }
        }

        return false;
    }

    /**
     * @param array  $value
     * @param string $expected
     *
     * @return bool
     */
    private function calculateHasTranslatableStringValue(array $value, string $expected): bool
    {
        foreach ($value as $key => $translation) {
            if (false !== mb_strpos($translation, $expected)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array  $value
     * @param string $expected
     *
     * @return bool
     */
    private function calculateEqualTranslatableStringValue(array $value, string $expected): bool
    {
        foreach ($value as $key => $translation) {
            if ($translation === $expected) {
                return true;
            }
        }

        return false;
    }
}
