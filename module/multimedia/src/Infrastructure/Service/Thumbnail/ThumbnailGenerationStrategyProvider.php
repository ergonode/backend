<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Service\Thumbnail;

class ThumbnailGenerationStrategyProvider
{
    /**
     * @var ThumbnailGenerationStrategyInterface[]
     */
    private array $strategies;

    public function __construct(ThumbnailGenerationStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    public function provide(string $type): ThumbnailGenerationStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supported($type)) {
                return $strategy;
            }
        }

        throw new \RuntimeException(sprintf('Can\'t find thumbnail generation strategy for %s type', $type));
    }
}
