<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Domain\Generator;

use Ergonode\Reader\Domain\Entity\Reader;

/**
 */
class ReaderGenerator
{
    /**
     * @var ReaderGeneratorStrategyInterface ...$strategies
     */
    private array $strategies;

    /**
     * @param ReaderGeneratorStrategyInterface ...$strategies
     */
    public function __construct(ReaderGeneratorStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param string $type
     *
     * @return Reader
     */
    public function generate(string $type): Reader
    {
        foreach ($this->strategies as $strategy) {
            if (strtoupper($type) === $strategy->getType()) {
                return $strategy->generate();
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find reader %s generator ', $type));
    }
}
