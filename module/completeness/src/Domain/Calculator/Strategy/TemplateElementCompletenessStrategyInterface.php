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
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

interface TemplateElementCompletenessStrategyInterface
{
    public function supports(string $variant): bool;

    public function getElementCompleteness(
        AbstractProduct $product,
        Language $language,
        TemplateElementInterface $element
    ): ?CompletenessCalculatorLine;
}
