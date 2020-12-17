<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Domain\Calculator\Strategy;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\ValueObject\TemplateElement\AttributeTemplateElementProperty;
use Ergonode\Designer\Domain\ValueObject\TemplateElementPropertyInterface;
use Ergonode\Completeness\Domain\Calculator\CompletenessCalculatorLine;
use Ergonode\Product\Domain\Entity\AbstractProduct;

interface TemplateElementCompletenessStrategyInterface
{
    public function supports(string $variant): bool;

    /**
     * @param TemplateElementPropertyInterface|AttributeTemplateElementProperty $properties
     */
    public function getElementCompleteness(
        AbstractProduct $product,
        Language $language,
        TemplateElementPropertyInterface $properties
    ): ?CompletenessCalculatorLine;
}
