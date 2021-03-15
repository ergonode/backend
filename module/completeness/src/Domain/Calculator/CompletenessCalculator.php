<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Domain\Calculator;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Designer\Domain\Entity\Element\AttributeTemplateElement;

class CompletenessCalculator
{
    private AttributeTemplateElementCompletenessCalculator $calculator;

    public function __construct(AttributeTemplateElementCompletenessCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @return CompletenessCalculatorLine[]
     */
    public function calculate(AbstractProduct $product, Template $template, Language $language): array
    {
        $result = [];
        foreach ($template->getElements() as $element) {
            if ($element instanceof AttributeTemplateElement) {
                $result[]  = $this->calculator->calculate($product, $language, $element);
            }
        }

        return $result;
    }
}
