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
use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Webmozart\Assert\Assert;
use Ergonode\Completeness\Domain\Calculator\CompletenessCalculatorLine;

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
        ProductDraft $draft,
        Language $language,
        TemplateElementPropertyInterface $properties
    ): ?CompletenessCalculatorLine {
        if (!$properties instanceof AttributeTemplateElementProperty) {
            throw new \LogicException('Object of wrong class');
        }
        $attribute = $this->repository->load($properties->getAttributeId());
        Assert::notNull($attribute, sprintf('Can\'t find attribute %s', $properties->getAttributeId()->getValue()));
        $value = $draft->hasAttribute($attribute->getCode()) ? $draft->getAttribute($attribute->getCode()) : null;

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
