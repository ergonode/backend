<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Channel\Domain\Service;

use Ergonode\Product\Domain\Entity\AbstractProduct;

interface ChannelServiceInterface
{
    /**
     * @param AbstractProduct $product
     */
    public function process(AbstractProduct $product): void;
}
