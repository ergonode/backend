<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

interface ProductCrossSellingQueryInterface
{
    /**
     * @param ProductId[] $productIds
     *
     * @return array
     */
    public function getOthersElements(
        ChannelId $channelId,
        ProductCollectionId $productCollectionId,
        array $productIds
    ): array;

    /**
     * @param ProductCollectionId[]     $productCollectionIds
     *
     * @return string[]
     */
    public function getOthersCollection(
        ChannelId $channelId,
        array $productCollectionIds
    ): array;
}
