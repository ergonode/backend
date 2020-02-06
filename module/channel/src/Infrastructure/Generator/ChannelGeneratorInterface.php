<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Generator;

use Ergonode\Channel\Domain\Entity\Channel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 *
 */
interface ChannelGeneratorInterface
{
    /**
     * @param ChannelId $channelId
     * @param string    $name
     *
     * @return Channel
     */
    public function generate(ChannelId $channelId, string $name): Channel;

    /**
     * @return string
     */
    public function getType(): string;
}
