<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Domain\Calculator\Strategy;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\ValueObject\TemplateElement\UiTemplateElementProperty;
use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use Ergonode\Completeness\Domain\Calculator\CompletenessCalculatorLine;
use Ergonode\Product\Domain\Entity\AbstractProduct;

class UiTemplateElementCompletenessStrategy implements TemplateElementCompletenessStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(string $variant): bool
    {
        return UiTemplateElementProperty::VARIANT === $variant;
    }

    /**
     * {@inheritDoc}
     */
    public function getElementCompleteness(
        AbstractProduct $product,
        Language $language,
        TemplateElementPropertyInterface $properties
    ): ?CompletenessCalculatorLine {
        return null;
    }
}
