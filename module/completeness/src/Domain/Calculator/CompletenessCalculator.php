<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Domain\Calculator;

use Ergonode\Completeness\Domain\Provider\TemplateElementCompletenessStrategyProvider;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Product\Domain\Entity\AbstractProduct;

class CompletenessCalculator
{
    private TemplateElementCompletenessStrategyProvider $provider;

    public function __construct(TemplateElementCompletenessStrategyProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return CompletenessCalculatorLine[]
     */
    public function calculate(AbstractProduct $product, Template $template, Language $language): array
    {
        $result = [];
        foreach ($template->getElements() as $element) {
            $strategy = $this->provider->provide($element->getType());
            $elementCompleteness = $strategy->getElementCompleteness($product, $language, $element);
            if ($elementCompleteness) {
                $result[] = $elementCompleteness;
            }
        }

        return $result;
    }
}
