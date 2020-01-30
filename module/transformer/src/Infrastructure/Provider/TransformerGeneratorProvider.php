<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Provider;

use Ergonode\Transformer\Infrastructure\Generator\TransformerGeneratorStrategyInterface;

/**
 */
class TransformerGeneratorProvider
{
    /**
     * @var TransformerGeneratorStrategyInterface[]
     */
    private array $strategies;

    /**
     * @param TransformerGeneratorStrategyInterface ...$strategies
     */
    public function __construct(TransformerGeneratorStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param string $type
     *
     * @return TransformerGeneratorStrategyInterface
     */
    public function provide(string $type): TransformerGeneratorStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if (strtoupper($type) === $strategy->getType()) {
                return $strategy;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find transformer %s generator', $type));
    }
}
