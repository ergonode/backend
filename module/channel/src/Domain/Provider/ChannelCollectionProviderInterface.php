<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Channel\Domain\Provider;

use Ergonode\Channel\Domain\Entity\Channel;

/**
 */
interface ChannelCollectionProviderInterface
{
    /**
     * @return Channel[]
     */
    public function provide(): array;
}
