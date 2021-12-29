<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Webmozart\Assert\Assert;
use Ergonode\Condition\Domain\Condition\AttributeTranslationExistsCondition;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;

class AttributeTranslationExistsConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
{
    private AttributeRepositoryInterface $repository;

    private TranslationInheritanceCalculator $calculator;

    public function __construct(AttributeRepositoryInterface $repository, TranslationInheritanceCalculator $calculator)
    {
        $this->repository = $repository;
        $this->calculator = $calculator;
    }

    public function supports(string $type): bool
    {
        return AttributeTranslationExistsCondition::TYPE === $type;
    }

    public function calculate(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        if (!$configuration instanceof AttributeTranslationExistsCondition) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    AttributeTranslationExistsCondition::class,
                    get_debug_type($configuration)
                )
            );
        }
        $attributeId = $configuration->getAttribute();
        $language = $configuration->getLanguage();
        $attribute = $this->repository->load($attributeId);

        Assert::notNull($attribute);

        $code = $attribute->getCode();

        if ($object->hasAttribute($code)) {
            $value = $this->calculator->calculate($attribute->getScope(), $object->getAttribute($code), $language);
            if (null !== $value && [] !== $value) {
                return true;
            }
        }

        return false;
    }
}
