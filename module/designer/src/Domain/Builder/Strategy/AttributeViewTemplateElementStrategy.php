<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Builder\Strategy;

use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;
use Ergonode\Attribute\Domain\Provider\AttributeParametersProvider;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Builder\BuilderTemplateElementStrategyInterface;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\Designer\Domain\View\ViewTemplateElement;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Designer\Domain\Entity\Element\AttributeTemplateElement;

class AttributeViewTemplateElementStrategy implements BuilderTemplateElementStrategyInterface
{
    private AttributeRepositoryInterface $attributeRepository;

    private AttributeParametersProvider $provider;

    private OptionQueryInterface $query;

    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        AttributeParametersProvider $provider,
        OptionQueryInterface $query
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->provider = $provider;
        $this->query = $query;
    }

    public function isSupported(string $type): bool
    {
        return AttributeTemplateElement::TYPE === $type;
    }

    public function build(TemplateElementInterface $element, Language $language): ViewTemplateElement
    {
        /** @var AttributeTemplateElement $element */
        Assert::isInstanceOf($element, AttributeTemplateElement::class);
        $attribute = $this->attributeRepository->load($element->getAttributeId());

        Assert::notNull($attribute);

        $label = $attribute->getLabel()->has($language)
            ? $attribute->getLabel()->get($language)
            : $attribute->getCode()->getValue();

        $properties = [
            'attribute_id' => $attribute->getId()->getValue(),
            'attribute_code' => $attribute->getCode()->getValue(),
            'required' => $element->isRequired(),
            'hint' => $attribute->getHint()->get($language),
            'placeholder' => $attribute->getPlaceholder()->get($language),
            'scope' => $attribute->getScope()->getValue(),
        ];


        if ($parameters = $this->provider->provide($attribute)) {
            $properties['parameters'] = $parameters;
        }

        if ($attribute instanceof AbstractOptionAttribute) {
            $options = [];
            foreach ($this->query->getAll($attribute->getId()) as $option) {
                $options[$option['id']] = [
                    'code' => $option['code'],
                    'label' => $option['label'][$language->getCode()] ?? null,
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
            $attribute->getType(),
            $properties
        );
    }
}
