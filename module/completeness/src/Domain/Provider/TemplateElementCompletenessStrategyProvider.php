<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Domain\Provider;

use Ergonode\Completeness\Domain\Calculator\Strategy\TemplateElementCompletenessStrategyInterface;

/**
 */
class TemplateElementCompletenessStrategyProvider
{
    /**
     * @var TemplateElementCompletenessStrategyInterface[]
     */
    private array $strategies;

    /**
     * @param TemplateElementCompletenessStrategyInterface ...$strategies
     */
    public function __construct(TemplateElementCompletenessStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param string $variant
     *
     * @return TemplateElementCompletenessStrategyInterface
     */
    public function provide(string $variant): TemplateElementCompletenessStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($variant)) {
                return $strategy;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find Template Element Completeness Strategy for "%s"', $variant));
    }
}
