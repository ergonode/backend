<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

/**
 */
interface Shopware6CategoryQueryInterface
{
    /**
     * @param ChannelId $channel
     * @param string    $shopwareId
     *
     * @return CategoryId|null
     */
    public function loadByShopwareId(ChannelId $channel, string $shopwareId): ?CategoryId;

    /**
     * @param ChannelId          $channel
     * @param \DateTimeImmutable $dateTime
     */
    public function cleanData(ChannelId $channel, \DateTimeImmutable $dateTime): void;

    /**
     * @param ChannelId $channelId
     * @param array     $categoryIds
     *
     * @return array
     */
    public function getCategoryToDelete(ChannelId $channelId, array $categoryIds):array;
}
