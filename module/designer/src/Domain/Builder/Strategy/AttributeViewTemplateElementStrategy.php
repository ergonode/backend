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
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Builder\BuilderTemplateElementStrategyInterface;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\Designer\Domain\ValueObject\TemplateElement\AttributeTemplateElementProperty;
use Ergonode\Designer\Domain\View\ViewTemplateElement;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;

/**
 */
class AttributeViewTemplateElementStrategy implements BuilderTemplateElementStrategyInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $attributeRepository;

    /**
     * @var AttributeParametersProvider
     */
    private AttributeParametersProvider $provider;

    /**
     * @var OptionQueryInterface
     */
    private OptionQueryInterface $query;

    /**
     * @param AttributeRepositoryInterface $attributeRepository
     * @param AttributeParametersProvider  $provider
     * @param OptionQueryInterface         $query
     */
    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        AttributeParametersProvider $provider,
        OptionQueryInterface $query
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->provider = $provider;
        $this->query = $query;
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
            foreach ($this->query->getAll($attribute->getId()) as $option) {
                $label = $option['label'][$language->getCode()] ?? null;
                $options[$option['id']] = [
                    'code' => $option['code'],
                    'label' => $label,
                ];
            }

            if (!empty($options)) {
                $properties['options'] = $options;
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
