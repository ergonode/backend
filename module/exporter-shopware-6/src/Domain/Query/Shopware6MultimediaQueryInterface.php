<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

/**
 */
interface Shopware6MultimediaQueryInterface
{
    /**
     * @param ChannelId $channel
     * @param string    $shopwareId
     *
     * @return MultimediaId|null
     */
    public function loadByShopwareId(ChannelId $channel, string $shopwareId): ?MultimediaId;

    /**
     * @param ChannelId          $channel
     * @param \DateTimeImmutable $dateTime
     */
    public function cleanData(ChannelId $channel, \DateTimeImmutable $dateTime): void;
}
