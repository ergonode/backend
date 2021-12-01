<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Condition\Infrastructure\Condition\Calculator;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Infrastructure\Condition\ConditionCalculatorStrategyInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Webmozart\Assert\Assert;
use Ergonode\Condition\Domain\Condition\LanguageAttributeExistsCondition;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;

class LanguageAttributeExistsConditionCalculatorStrategy implements ConditionCalculatorStrategyInterface
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
        return LanguageAttributeExistsCondition::TYPE === $type;
    }

    public function calculate(AbstractProduct $object, ConditionInterface $configuration): bool
    {
        if (!$configuration instanceof LanguageAttributeExistsCondition) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    LanguageAttributeExistsCondition::class,
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
