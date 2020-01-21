<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Provider;

use Ergonode\Channel\Infrastructure\Exception\ChannelGeneratorProviderNotFoundException;
use Ergonode\Channel\Infrastructure\Generator\ChannelGeneratorInterface;

/**
 */
class ChannelGeneratorProvider
{
    /**
     * @var ChannelGeneratorInterface[]
     */
    private array $generators;

    /**
     * @param ChannelGeneratorInterface ...$generators
     */
    public function __construct(ChannelGeneratorInterface ...$generators)
    {
        $this->generators = $generators;
    }

    /**
     * @param string $type
     *
     * @return ChannelGeneratorInterface
     *
     * @throws ChannelGeneratorProviderNotFoundException
     */
    public function provide(string $type): ChannelGeneratorInterface
    {
        foreach ($this->generators as $generator) {
            if (strtoupper($type) === $generator->getType()) {
                return $generator;
            }
        }

        throw new ChannelGeneratorProviderNotFoundException(sprintf('Can\'t find channel %s generator ', $type));
    }
}
