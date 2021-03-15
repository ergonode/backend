<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

interface CategoryQueryInterface
{
    public function loadByShopwareId(ChannelId $channel, string $shopwareId): ?CategoryId;

    public function cleanData(ChannelId $channel, \DateTimeImmutable $dateTime): void;

    /**
     * @param array $categoryIds
     *
     * @return array
     */
    public function getCategoryToDelete(ChannelId $channelId, array $categoryIds): array;
}
