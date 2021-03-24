<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Domain\Service;

use Ergonode\Product\Domain\Entity\AbstractProduct;

interface ChannelServiceInterface
{
    public function process(AbstractProduct $product): void;
}
