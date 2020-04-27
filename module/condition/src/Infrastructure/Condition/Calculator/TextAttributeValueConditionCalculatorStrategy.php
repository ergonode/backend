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
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Webmozart\Assert\Assert;

/**
 */
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
                if ($value instanceof TranslatableString) {
                    return $this->calculateEqualTranslatableStringValue($value, $expected);
                }

                return $expected === $value;
            }

            if ('~' === $option) {
                if ($value instanceof TranslatableString) {
                    return $this->calculateHasTranslatableStringValue($value, $expected);
                }

                return (false !== mb_strpos($value, $expected));
            }
        }

        return false;
    }

    /**
     * @param TranslatableString $value
     * @param string             $expected
     *
     * @return bool
     */
    private function calculateHasTranslatableStringValue(TranslatableString $value, string $expected): bool
    {
        foreach ($value->getTranslations() as $translation) {
            if (false !== mb_strpos($translation, $expected)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param TranslatableString $value
     * @param string             $expected
     *
     * @return bool
     */
    private function calculateEqualTranslatableStringValue(TranslatableString $value, string $expected): bool
    {
        foreach ($value->getTranslations() as $translation) {
            if ($translation === $expected) {
                return true;
            }
        }

        return false;
    }
}
