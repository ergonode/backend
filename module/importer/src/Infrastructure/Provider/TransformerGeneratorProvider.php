<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Provider;

use Ergonode\Importer\Infrastructure\Generator\TransformerGeneratorStrategyInterface;

class TransformerGeneratorProvider
{
    /**
     * @var TransformerGeneratorStrategyInterface[]
     */
    private array $strategies;

    public function __construct(TransformerGeneratorStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    public function provide(string $type): TransformerGeneratorStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($type === $strategy->getType()) {
                return $strategy;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find transformer "%s" generator', $type));
    }
}
