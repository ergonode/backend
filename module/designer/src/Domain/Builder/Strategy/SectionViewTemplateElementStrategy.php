<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Builder\Strategy;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Builder\BuilderTemplateElementStrategyInterface;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\Designer\Domain\View\ViewTemplateElement;
use Ergonode\Designer\Domain\Entity\Element\UiTemplateElement;
use Webmozart\Assert\Assert;

class SectionViewTemplateElementStrategy implements BuilderTemplateElementStrategyInterface
{
    public function isSupported(string $type): bool
    {
        return UiTemplateElement::TYPE === $type;
    }

    public function build(TemplateElementInterface $element, Language $language): ViewTemplateElement
    {
        /** @var UiTemplateElement $element */
        Assert::isInstanceOf($element, UiTemplateElement::class);

        return new ViewTemplateElement(
            $element->getPosition(),
            $element->getSize(),
            $element->getLabel(),
            'SECTION'
        );
    }
}
