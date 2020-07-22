<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Query;

use Ergonode\ExporterShopware6\Domain\Entity\Catalog\Shopware6Category;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
interface Shopware6CategoryQueryInterface
{
    /**
     * @param ChannelId $channel
     * @param string    $shopwareId
     *
     * @return Shopware6Category|null
     */
    public function loadByShopwareId(ChannelId $channel, string $shopwareId): ?Shopware6Category;

    /**
     * @param ChannelId          $channel
     * @param \DateTimeImmutable $dateTime
     */
    public function clearBefore(ChannelId $channel, \DateTimeImmutable $dateTime): void;
}
