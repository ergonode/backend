<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Provider;

use Ergonode\Designer\Domain\Builder\BuilderTemplateElementStrategyInterface;
use Ergonode\Designer\Domain\Entity\TemplateElement;

/**
 */
class ViewTemplateElementProvider
{
    /**
     * @var BuilderTemplateElementStrategyInterface[]
     */
    private $strategies;

    /**
     * @param BuilderTemplateElementStrategyInterface ...$strategies
     */
    public function __construct(BuilderTemplateElementStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param TemplateElement $element
     *
     * @return BuilderTemplateElementStrategyInterface
     */
    public function provide(TemplateElement $element): BuilderTemplateElementStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->isSupported($element->getProperties()->getVariant(), $element->getType())) {
                return $strategy;
            }
        }

        throw new \RuntimeException(
            sprintf(
                'Can\'t find strategy for %s and %s ',
                $element->getProperties()->getVariant(),
                $element->getType()
            )
        );
    }
}
