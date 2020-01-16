<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Builder\Strategy;

use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;
use Ergonode\Attribute\Domain\Provider\AttributeParametersProvider;
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
     * @var AttributeParametersProvider
     */
    private $provider;

    /**
     * @param AttributeRepositoryInterface  $attributeRepository
     * @param TranslatedOptionValueResolver $resolver
     * @param AttributeParametersProvider   $provider
     */
    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        TranslatedOptionValueResolver $resolver,
        AttributeParametersProvider $provider
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->resolver = $resolver;
        $this->provider = $provider;
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

        $label = $attribute->getLabel()->has($language)
            ? $attribute->getLabel()->get($language)
            : $attribute->getCode()->getValue();

        $properties = [
            'attribute_id' => $attribute->getId()->getValue(),
            'attribute_code' => $attribute->getCode()->getValue(),
            'required' => $property->isRequired(),
            'hint' => $attribute->getHint()->get($language),
            'placeholder' => $attribute->getPlaceholder()->get($language),
        ];


        if ($parameters = $this->provider->provide($attribute)) {
            $properties['parameters'] = $parameters;
        }

        if ($attribute instanceof AbstractOptionAttribute) {
            $options = [];
            foreach ($attribute->getOptions() as $key => $option) {
                $options[$key] = $this->resolver->resolve($option, $language);
            }
            if (!empty($options)) {
                $properties['options'] = $options;
            } else {
                $properties['options'] = new \stdClass();
            }
        }

        return new ViewTemplateElement(
            $element->getPosition(),
            $element->getSize(),
            $label,
            $element->getType(),
            $properties
        );
    }
}
