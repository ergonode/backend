<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

interface ProductCrossSellingRepositoryInterface
{
    public function load(ChannelId $channelId, ProductCollectionId $productCollectionId, ProductId $productId): ?string;

    public function save(
        ChannelId $channelId,
        ProductCollectionId $productCollectionId,
        ProductId $productId,
        string $shopwareId
    ): void;

    public function exists(ChannelId $channelId, ProductCollectionId $productCollectionId, ProductId $productId): bool;

    public function delete(ChannelId $channelId, string $shopwareId): void;
}
