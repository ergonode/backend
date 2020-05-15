<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Domain\Calculator\Strategy;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Completeness\Domain\ReadModel\CompletenessElementReadModel;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\ValueObject\TemplateElement\AttributeTemplateElementProperty;
use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use Ergonode\Product\Infrastructure\Calculator\TranslationInheritanceCalculator;
use Webmozart\Assert\Assert;

/**
 */
class AttributeTemplateElementCompletenessStrategy implements TemplateElementCompletenessStrategyInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @var TranslationInheritanceCalculator
     */
    private TranslationInheritanceCalculator $calculator;

    /**
     * @param AttributeRepositoryInterface     $repository
     * @param TranslationInheritanceCalculator $calculator
     */
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
    ): ?CompletenessElementReadModel {
        Assert::isInstanceOf($properties, AttributeTemplateElementProperty::class);

        $attribute = $this->repository->load($properties->getAttributeId());
        Assert::notNull($attribute, sprintf('Can\'t find attribute %s', $properties->getAttributeId()->getValue()));
        $label = $attribute->getLabel();
        $name = $label->has($language) ? $label->get($language) : $attribute->getCode()->getValue();
        $value = $draft->hasAttribute($attribute->getCode()) ? $draft->getAttribute($attribute->getCode()) : null;

        if ($value) {
            $value = $this->calculator->calculate($attribute, $value, $language);
        }

        return new CompletenessElementReadModel($attribute->getId(), $name, $properties->isRequired(), $value);
    }
}
