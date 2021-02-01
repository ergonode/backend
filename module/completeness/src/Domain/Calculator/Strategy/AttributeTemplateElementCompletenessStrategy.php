<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Domain\Calculator\Strategy;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Webmozart\Assert\Assert;
use Ergonode\Completeness\Domain\Calculator\CompletenessCalculatorLine;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Designer\Domain\Entity\Element\AttributeTemplateElement;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

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
    public function supports(string $type): bool
    {
        return AttributeTemplateElement::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getElementCompleteness(
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
        $attribute = $this->repository->load($element->getAttributeId());
        Assert::notNull($attribute, sprintf('Can\'t find attribute %s', $element->getAttributeId()->getValue()));
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

        return new CompletenessCalculatorLine($attribute->getId(), $element->isRequired(), $filled);
    }
}
