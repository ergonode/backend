<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Builder\Strategy;

use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Attribute\Domain\Resolver\TranslatedOptionValueResolver;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Builder\BuilderTemplateElementStrategyInterface;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\Designer\Domain\ValueObject\TemplateElement\AttributeTemplateElementProperty;
use Ergonode\Designer\Domain\View\ViewTemplateElement;
use Webmozart\Assert\Assert;

/**
 */
class AttributeViewTemplateElementStrategy implements BuilderTemplateElementStrategyInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     * @var TranslatedOptionValueResolver
     */
    private $resolver;

    /**
     * @param AttributeRepositoryInterface  $attributeRepository
     * @param TranslatedOptionValueResolver $resolver
     */
    public function __construct(AttributeRepositoryInterface $attributeRepository, TranslatedOptionValueResolver $resolver)
    {
        $this->attributeRepository = $attributeRepository;
        $this->resolver = $resolver;
    }

    /**
     * @param string $variant
     * @param string $type
     *
     * @return bool
     */
    public function isSupported(string $variant, string $type): bool
    {
        return AttributeTemplateElementProperty::VARIANT === $variant;
    }

    /**
     * @param TemplateElement $element
     * @param Language        $language
     *
     * @return ViewTemplateElement
     */
    public function build(TemplateElement $element, Language $language): ViewTemplateElement
    {
        /** @var AttributeTemplateElementProperty $property */
        $property = $element->getProperties();

        $attribute = $this->attributeRepository->load($property->getAttributeId());

        Assert::notNull($attribute);

        $label = $attribute->getLabel()->has($language) ? $attribute->getLabel()->get($language) : $attribute->getCode()->getValue();

        $parameters = [
            'attribute_id' => $attribute->getId()->getValue(),
            'required' => $property->isRequired(),
            'hint' => $attribute->getHint()->get($language),
            'placeholder' => $attribute->getPlaceholder()->get($language),
        ];


        if ($attribute instanceof AbstractOptionAttribute) {
            $options = [];
            foreach ($attribute->getOptions() as $key => $option) {
                $options[$key] = $this->resolver->resolve($option, $language);
            }
            $parameters['options'] = $options;
        }

        return new ViewTemplateElement(
            $element->getPosition(),
            $element->getSize(),
            $label,
            $element->getType(),
            $parameters
        );
    }
}
