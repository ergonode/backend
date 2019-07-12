<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Domain\Calculator\Strategy;

use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Completeness\Domain\ReadModel\CompletenessElementReadModel;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\ValueObject\TemplateElement\AbstractTemplateElementProperty;
use Ergonode\Designer\Domain\ValueObject\TemplateElement\AttributeTemplateElementProperty;
use Ergonode\Editor\Domain\Entity\ProductDraft;
use Webmozart\Assert\Assert;

/**
 */
class AttributeTemplateElementCompletenessStrategy implements TemplateElementCompletenessStrategyInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    private $repository;

    /**
     * @param AttributeRepositoryInterface $repository
     */
    public function __construct(AttributeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $variant
     *
     * @return bool
     */
    public function isSupported(string $variant): bool
    {
        return AttributeTemplateElementProperty::VARIANT === $variant;
    }

    /**
     * @param ProductDraft                                                     $draft
     * @param Language                                                         $language
     * @param AbstractTemplateElementProperty|AttributeTemplateElementProperty $properties
     *
     * @return CompletenessElementReadModel|null
     */
    public function getElementCompleteness(ProductDraft $draft, Language $language, AbstractTemplateElementProperty $properties): ?CompletenessElementReadModel
    {
        Assert::isInstanceOf($properties, AttributeTemplateElementProperty::class);

        $attribute = $this->repository->load($properties->getAttributeId());
        Assert::notNull($attribute, sprintf('Can\'t find attribute %s', $properties->getAttributeId()->getValue()));
        $name = $attribute->getLabel()->has($language) ? $attribute->getLabel()->get($language) : $attribute->getCode()->getValue();
        $value = $draft->hasAttribute($attribute->getCode()) ? (string) $draft->getAttribute($attribute->getCode()) : null;

        return new CompletenessElementReadModel($attribute->getId(), $name, $properties->isRequired(), $value);
    }
}
