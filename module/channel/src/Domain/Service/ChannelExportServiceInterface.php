<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Channel\Domain\Service;

use Ergonode\Channel\Domain\Entity\Channel;
use Ergonode\Product\Domain\Entity\AbstractProduct;

/**
 */
interface ChannelExportServiceInterface
{
    /**
     * @param Channel         $channel
     * @param AbstractProduct $product
     */
    public function process(Channel $channel, AbstractProduct $product): void;
}
