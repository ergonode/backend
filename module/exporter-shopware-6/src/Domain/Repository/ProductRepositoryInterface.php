<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

interface ProductRepositoryInterface
{
    public function load(ChannelId $channelId, ProductId $productId): ?string;

    public function save(ChannelId $channelId, ProductId $productId, string $shopwareId): void;

    public function exists(ChannelId $channelId, ProductId $productId): bool;
}
