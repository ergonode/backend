<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Domain\Calculator\Strategy;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\ValueObject\TemplateElement\AttributeTemplateElementProperty;
use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Webmozart\Assert\Assert;
use Ergonode\Completeness\Domain\Calculator\CompletenessCalculatorLine;
use Ergonode\Product\Domain\Entity\AbstractProduct;

class AttributeTemplateElementCompletenessStrategy implements TemplateElementCompletenessStrategyInterface
{
    private AttributeRepositoryInterface $repository;

    private TranslationInheritanceCalculator $calculator;

    public function __construct(
        AttributeRepositoryInterface $repository,
        TranslationInheritanceCalculator $calculator
    ) {
        $this->repository = $repository;
        $this->calculator = $calculator;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $variant): bool
    {
        return AttributeTemplateElementProperty::VARIANT === $variant;
    }

    /**
     * {@inheritDoc}
     */
    public function getElementCompleteness(
        AbstractProduct $product,
        Language $language,
        TemplateElementPropertyInterface $properties
    ): ?CompletenessCalculatorLine {
        if (!$properties instanceof AttributeTemplateElementProperty) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    AttributeTemplateElementProperty::class,
                    get_debug_type($properties)
                )
            );
        }
        $attribute = $this->repository->load($properties->getAttributeId());
        Assert::notNull($attribute, sprintf('Can\'t find attribute %s', $properties->getAttributeId()->getValue()));
        $value = $product->hasAttribute($attribute->getCode()) ? $product->getAttribute($attribute->getCode()) : null;

        $filled = false;
        if ($value) {
            $value = $this->calculator->calculate($attribute, $value, $language);
            if ('' !== $value
                && [] !== $value
                && null !== $value) {
                $filled = true;
            }
        }

        return new CompletenessCalculatorLine($attribute->getId(), $properties->isRequired(), $filled);
    }
}
