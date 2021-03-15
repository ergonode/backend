<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Provider;

use Ergonode\Designer\Domain\Builder\BuilderTemplateElementStrategyInterface;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

class ViewTemplateElementProvider
{
    /**
     * @var BuilderTemplateElementStrategyInterface[]
     */
    private array $strategies;

    public function __construct(BuilderTemplateElementStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    public function provide(TemplateElementInterface $element): BuilderTemplateElementStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->isSupported($element->getType())) {
                return $strategy;
            }
        }

        throw new \RuntimeException(
            sprintf(
                'Can\'t find strategy for %s',
                $element->getType(),
            )
        );
    }
}
