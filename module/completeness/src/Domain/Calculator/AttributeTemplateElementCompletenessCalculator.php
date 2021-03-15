<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Domain\Calculator;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Designer\Domain\Entity\Element\AttributeTemplateElement;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

class AttributeTemplateElementCompletenessCalculator
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

    public function supports(string $type): bool
    {
        return AttributeTemplateElement::TYPE === $type;
    }

    public function calculate(
        AbstractProduct $product,
        Language $language,
        TemplateElementInterface $element
    ): ?CompletenessCalculatorLine {
        if (!$element instanceof AttributeTemplateElement) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    AttributeTemplateElement::class,
                    get_debug_type($element)
                )
            );
        }

        $filled = false;
        if ($element->isRequired()) {
            $attributeId = $element->getAttributeId();
            $attribute = $this->repository->load($element->getAttributeId());
            Assert::notNull($attribute, sprintf('Can\'t find attribute %s', $attributeId->getValue()));
            $attributeCode = $attribute->getCode();
            $value = $product->hasAttribute($attributeCode) ? $product->getAttribute($attributeCode) : null;

            if ($value) {
                $value = $this->calculator->calculate($attribute->getScope(), $value, $language);
                if ('' !== $value
                    && [] !== $value
                    && null !== $value) {
                    $filled = true;
                }
            }
        }

        return new CompletenessCalculatorLine($element->getAttributeId(), $element->isRequired(), $filled);
    }
}
