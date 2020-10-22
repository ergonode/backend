<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

interface Shopware6ProductRepositoryInterface
{
    /**
     * @param ChannelId $channelId
     * @param ProductId $productId
     *
     * @return string|null
     */
    public function load(ChannelId $channelId, ProductId $productId): ?string;

    /**
     * @param ChannelId $channelId
     * @param ProductId $productId
     * @param string    $shopwareId
     */
    public function save(ChannelId $channelId, ProductId $productId, string $shopwareId): void;

    /**
     * @param ChannelId $channelId
     * @param ProductId $productId
     *
     * @return bool
     */
    public function exists(ChannelId $channelId, ProductId $productId): bool;
}
