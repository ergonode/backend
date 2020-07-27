<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
interface Shopware6CustomFieldQueryInterface
{
    /**
     * @param ChannelId $channel
     * @param string    $shopwareId
     *
     * @return AttributeId|null
     */
    public function loadByShopwareId(ChannelId $channel, string $shopwareId): ?AttributeId;

    /**
     * @param ChannelId          $channel
     * @param \DateTimeImmutable $dateTime
     */
    public function clearBefore(ChannelId $channel, \DateTimeImmutable $dateTime): void;
}
