<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Strategy\Template;

use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Webmozart\Assert\Assert;
use Ergonode\Designer\Domain\Entity\Element\UiTemplateElement;
use Ergonode\ExporterFile\Infrastructure\Processor\Strategy\TemplateElementMapInterface;

class LabelTemplateElementMapStrategy implements TemplateElementMapInterface
{
    public function support(TemplateElementInterface $element): bool
    {
        return UiTemplateElement::TYPE === $element->getType();
    }

    /**
     * @param UiTemplateElement $element
     */
    public function map(TemplateElementInterface $element): array
    {
        Assert::isInstanceOf($element, UiTemplateElement::class);

        return [
            'label' => $element->getLabel(),
        ];
    }
}
