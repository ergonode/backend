<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Domain\Calculator\Strategy;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Completeness\Domain\Calculator\CompletenessCalculatorLine;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Designer\Domain\Entity\Element\UiTemplateElement;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

class UiTemplateElementCompletenessStrategy implements TemplateElementCompletenessStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(string $type): bool
    {
        return UiTemplateElement::TYPE === $type;
    }

    /**
     * {@inheritDoc}
     */
    public function getElementCompleteness(
        AbstractProduct $product,
        Language $language,
        TemplateElementInterface $element
    ): ?CompletenessCalculatorLine {
        return null;
    }
}
