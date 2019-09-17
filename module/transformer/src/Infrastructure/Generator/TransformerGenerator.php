<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Generator;

use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Domain\Entity\TransformerId;

/**
 */
class TransformerGenerator
{
    /**
     * @var TransformerGeneratorStrategyInterface[]
     */
    private $strategies;

    /**
     * @param TransformerGeneratorStrategyInterface ...$strategies
     */
    public function __construct(TransformerGeneratorStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param string $name
     * @param string $type
     * @param array  $options
     *
     * @return Transformer
     * @throws \Exception
     */
    public function generate(string $name, string $type, array $options = []): Transformer
    {
        foreach ($this->strategies as $strategy) {
            if (strtoupper($type) === $strategy->getType()) {
                return $strategy->generate(TransformerId::fromKey($type), $name, $type, $options);
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find transformer %s generator', $type));
    }
}
