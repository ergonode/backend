<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Builder\Strategy;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Builder\BuilderTemplateElementStrategyInterface;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\Designer\Domain\ValueObject\TemplateElement\UiTemplateElementProperty;
use Ergonode\Designer\Domain\View\ViewTemplateElement;

class SectionViewTemplateElementStrategy implements BuilderTemplateElementStrategyInterface
{
    /**
     * @param string $variant
     * @param string $type
     *
     * @return bool
     */
    public function isSupported(string $variant, string $type): bool
    {
        return UiTemplateElementProperty::VARIANT === $variant && 'SECTION' === $type;
    }

    /**
     * @param TemplateElement $element
     * @param Language        $language
     *
     * @return ViewTemplateElement
     */
    public function build(TemplateElement $element, Language $language): ViewTemplateElement
    {
        /** @var UiTemplateElementProperty $property */
        $property = $element->getProperties();

        return new ViewTemplateElement(
            $element->getPosition(),
            $element->getSize(),
            $property->getLabel(),
            $element->getType()
        );
    }
}
