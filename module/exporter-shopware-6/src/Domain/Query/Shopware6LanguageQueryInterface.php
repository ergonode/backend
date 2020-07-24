<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
interface Shopware6LanguageQueryInterface
{
    /**
     * @param ChannelId          $channelId
     * @param \DateTimeImmutable $dateTime
     */
    public function clearBefore(ChannelId $channelId, \DateTimeImmutable $dateTime): void;
}
