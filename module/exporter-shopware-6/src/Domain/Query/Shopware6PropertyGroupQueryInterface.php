<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

interface Shopware6PropertyGroupQueryInterface
{
    /**
     * @param ChannelId $channelId
     * @param string    $shopwareId
     *
     * @return AttributeId|null
     */
    public function loadByShopwareId(ChannelId $channelId, string $shopwareId): ?AttributeId;

    /**
     * @param ChannelId          $channelId
     * @param \DateTimeImmutable $dateTime
     * @param string             $type
     */
    public function cleanData(ChannelId $channelId, \DateTimeImmutable $dateTime, string  $type): void;
}
